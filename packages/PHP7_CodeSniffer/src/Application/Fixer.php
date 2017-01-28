<?php declare(strict_types=1);

namespace Symplify\PHP7_CodeSniffer\Application;

use Symplify\PHP7_CodeSniffer\File\File;

final class Fixer
{
    /**
     * @var File
     */
    private $currentFile;

    /**
     * @var string[]|array<int, string>
     */
    private $tokens = [];

    public function startFile(File $file)
    {
        $this->currentFile = $file;

        $tokens = $file->getTokens();

        $this->tokens = [];
        foreach ($tokens as $index => $token) {
            if (isset($token['orig_content']) === true) {
                $this->tokens[$index] = $token['orig_content'];
            } else {
                $this->tokens[$index] = $token['content'];
            }
        }
    }

    public function getContents() : string
    {
        return implode($this->tokens);
    }

    public function getTokenContent(int $stackPtr) : string
    {
        return $this->tokens[$stackPtr];
    }

    public function replaceToken(int $stackPtr, string $content) : bool
    {
        $this->tokens[$stackPtr] = $content;
        return true;
    }

    public function addContent(int $stackPtr, string $content) : bool
    {
        $current = $this->getTokenContent($stackPtr);
        return $this->replaceToken($stackPtr, $current.$content);
    }

    public function addContentBefore(int $stackPtr, string $content) : bool
    {
        $current = $this->getTokenContent($stackPtr);
        return $this->replaceToken($stackPtr, $content.$current);
    }

    public function addNewline(int $stackPtr) : bool
    {
        return $this->addContent($stackPtr, PHP_EOL);
    }

    public function addNewlineBefore(int $stackPtr) : bool
    {
        return $this->addContentBefore($stackPtr, PHP_EOL);
    }

    public function substrToken(int $stackPtr, int $start, int $length = null) : bool
    {
        $current = $this->getTokenContent($stackPtr);

        if ($length !== null) {
            $newContent = substr($current, $start, $length);
        } else {
            $newContent = substr($current, $start);
        }

        return $this->replaceToken($stackPtr, $newContent);
    }

    /**
     * Needed for legacy compatibility.
     */
    public function beginChangeset()
    {
    }

    /**
     * Needed for legacy compatibility.
     */
    public function endChangeset()
    {
    }
}
