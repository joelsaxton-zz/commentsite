<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Game of Thrones Comment Site</title>
</head>
<body>
<div id="myApplication"></div>
</body>
<script src="{{asset('js/app.js')}}"></script>
<script>
    window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
    ]); ?>
</script>
</html>