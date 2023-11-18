<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
        name="description"
        content="Swagger Documentation CFS3"
    />
    <link rel="shortcut icon" href="<?= asset('images/logo.png') ?>" />
    <title>Documentation API CFS3</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui.css" />
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js" crossorigin></script>
<script>
    window.onload = () => {
        window.ui = SwaggerUIBundle({
            url: '<?= url('api/documentation/v1.json') ?>',
            dom_id: '#swagger-ui',
            persistAuthorization: true,
        });
    };
</script>
</body>
</html>
