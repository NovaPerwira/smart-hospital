<?php

namespace App\Support\Filesystem;

use Illuminate\Filesystem\Filesystem;

class WindowsFriendlyFilesystem extends Filesystem
{
    /**
     * Replace the given file with new content.
     *
     * On Windows, atomic replace via `tempnam() + rename()` can fail due to
     * filesystem/AV locking and differing rename semantics. We fall back to a
     * direct write which is reliable for local development on Windows.
     */
    public function replace($path, $content, $mode = null)
    {
        if (\DIRECTORY_SEPARATOR === '\\') {
            file_put_contents($path, $content);

            if (! is_null($mode)) {
                @chmod($path, $mode);
            }

            return;
        }

        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        if (! is_null($mode)) {
            chmod($tempPath, $mode);
        } else {
            chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);
        rename($tempPath, $path);
    }
}

