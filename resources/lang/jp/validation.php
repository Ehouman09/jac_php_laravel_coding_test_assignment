<?php

//I used the Deepl translator to translate this text, 
//so some texts may be incorrectly translated.

return [
    'email' => [
        'required' => 'メールアドレスは必須です。',
        'email' => '有効なメールアドレスを入力してください。',
        'max' => 'メールアドレスは50文字以内で入力してください。',
        'unique' => 'このメールアドレスは既に使用されています。',
    ],
    'password' => [
        'required' => 'パスワードは必須です。',
        'min' => 'パスワードは6文字以上で入力してください。',
        'confirmed' => 'パスワード確認が一致しません。',
    ],
    'name' => [
        'required' => '名前は必須です。',
        'string' => '名前は文字列で入力してください。',
        'max' => '名前は100文字以内で入力してください。',
    ],
    'title' => [
        'required' => 'タイトルは必須です。',
        'max' => 'タイトルは255文字以内で入力してください。',
    ],
    'author' => [
        'required' => '著者は必須です。',
        'max' => '著者名は255文字以内で入力してください。',
    ],
    'description' => [
        'required' => '説明は必須です。',
    ],
    'category_id' => [
        'exists' => '選択されたカテゴリーは無効です。',
    ],
    'publication_year' => [
        'required' => '出版年は必須です。',
        'integer' => '出版年は有効な整数値である必要があります。',
        'min' => '出版年は1000以上である必要があります。',
        'max' => '出版年は現在の年以下である必要があります。',
    ],
    'cover_image' => [
        'image' => '表紙画像は画像ファイルである必要があります。',
        'max' => '表紙画像のサイズは2MB以下である必要があります。',
        'mimes' => '表紙画像はJPEG、PNG、またはJPG形式である必要があります。',
        'required' => '表紙画像は必須です。',
    ],
];