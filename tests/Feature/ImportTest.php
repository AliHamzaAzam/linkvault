<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Bookmark Import', function () {
    it('imports valid HTML file', function () {
        $html = <<<'HTML'
<!DOCTYPE NETSCAPE-Bookmark-file-1>
<HTML>
<H1>Bookmarks</H1>
<DT><H3>Favorites</H3>
<DL><p>
    <DT><A HREF="https://example.com">Example</A>
    <DT><A HREF="https://test.com">Test Site</A>
</DL><p>
</HTML>
HTML;

        $file = UploadedFile::fake()->createWithContent('bookmarks.html', $html);

        $response = $this->post('/import', [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('bookmarks', [
            'url' => 'https://example.com',
            'user_id' => $this->user->id,
        ]);
        
        $this->assertDatabaseHas('bookmarks', [
            'url' => 'https://test.com',
            'user_id' => $this->user->id,
        ]);
    });

    it('creates collections from folders', function () {
        $html = <<<'HTML'
<!DOCTYPE NETSCAPE-Bookmark-file-1>
<HTML>
<H1>Bookmarks</H1>
<DT><H3>Dev Tools</H3>
<DL><p>
    <DT><A HREF="https://github.com">GitHub</A>
</DL><p>
</HTML>
HTML;

        $file = UploadedFile::fake()->createWithContent('bookmarks.html', $html);

        $this->post('/import', ['file' => $file]);

        $this->assertDatabaseHas('collections', [
            'name' => 'Dev Tools',
            'user_id' => $this->user->id,
        ]);
    });

    it('skips duplicate URLs', function () {
        $html = <<<'HTML'
<!DOCTYPE NETSCAPE-Bookmark-file-1>
<HTML>
<DT><A HREF="https://example.com">Example</A>
<DT><A HREF="https://example.com">Example Duplicate</A>
</HTML>
HTML;

        $file = UploadedFile::fake()->createWithContent('bookmarks.html', $html);

        $this->post('/import', ['file' => $file]);

        $count = \App\Models\Bookmark::where('url', 'https://example.com')
            ->where('user_id', $this->user->id)
            ->count();
        
        expect($count)->toBe(1);
    });

    it('rejects non-HTML files', function () {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->post('/import', [
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
    });

    it('rejects oversized files', function () {
        $file = UploadedFile::fake()->create('large.html', 6000, 'text/html');

        $response = $this->post('/import', [
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
    });
});
