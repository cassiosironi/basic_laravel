<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $config->nome_sistema; ?></title>
  <meta name="keywords" content="">
  <meta name="description" content="<?php echo $config->nome_sistema; ?>">
  <meta name="author" content="Cássio Sironi">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="content-language" content="pt-br" />
  <meta name="copyright" content="<?php echo $config->nome_sistema . ' | ' . date('Y'); ?>" />
  <meta property="og:image" content="<?php echo asset($config->logo); ?>" />
  <meta property="og:title" content="<?php echo $config->nome_sistema; ?>" />
  <meta property="og:description" content="<?php echo $config->nome_sistema . ' | ' . date('Y'); ?>" />
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo asset($config->favicon); ?>">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/style_site.css') }}">

</head>
<body>