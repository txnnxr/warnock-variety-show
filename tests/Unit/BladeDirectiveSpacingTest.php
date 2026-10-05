<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BladeDirectiveSpacingTest extends TestCase
{
    /**
     * Blade ignores a directive glued to a preceding word ("Seats@if(...)"),
     * so it renders as raw text or breaks the template. Require a space.
     */
    public function test_no_blade_directive_is_glued_to_a_word(): void
    {
        $views = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../../resources/views'));
        $offenders = [];

        foreach ($views as $file) {
            if (! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }

            foreach (file($file->getPathname()) as $number => $line) {
                if (preg_match('/[A-Za-z0-9]@(if|elseif|else|endif|foreach|endforeach|can|endcan|cannot|endcannot|isset|endisset|unless|endunless|auth|endauth|guest|endguest)\b/', $line)) {
                    $offenders[] = $file->getFilename().':'.($number + 1).': '.trim($line);
                }
            }
        }

        $this->assertSame([], $offenders, "Blade directives glued to a word:\n".implode("\n", $offenders));
    }
}
