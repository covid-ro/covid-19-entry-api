<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>COVID-19 Monitor API</title>
    <link rel="stylesheet" type="text/css" href="/swagger/swagger-ui.css">
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>

<script src="/swagger/swagger-ui-bundle.js"></script>
<script src="/swagger/swagger-ui-standalone-preset.js"></script>
<script>
    window.onload = function () {
        const ui = SwaggerUIBundle({
            url: "/swagger/covid-19-api.json",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "BaseLayout"
        });

        window.ui = ui
    }
</script>
</body>
</html>
