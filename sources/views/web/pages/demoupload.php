<html>

<head>
    <title>Multiple File Upload Demo Application</title>
</head>

<body>
    <form action="<?= url('api/document/upload') ?>" method="POST" enctype="multipart/form-data">
        <div id="fileSection">
            <h1>User File Upload Demo</h1>
            <table>
                <tr>
                    <td>Document Name:</td>
                    <td><input type="text" name="name" />
                    </td>
                </tr>
                <tr>
                    <td>Category id:</td>
                    <td><input type="text" name="category_id" />
                    </td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><input type="text" name="description" />
                    </td>
                </tr>
                <tr>
                    <td>Upload file</td>
                    <td><input type="file" name="file" />
                    </td>
                </tr>


            </table>
        </div>
        <input type="submit" value="Upload" />
    </form>
</body>

</html>