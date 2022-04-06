<?php

namespace Tests\Feature;

use Tests\TestCase;

class CommandTest extends TestCase
{
    public function testコマンド実行()
    {
        //ユーザ登録、RSS登録した後で実行。RFC違反メールのテスト
        $this->artisan('getrss')
            ->assertExitCode(0);
    }
}
