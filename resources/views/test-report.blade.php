<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Résultats des Tests</h1>
    <table>
        <thead>
            <tr>
                <th>Test</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tests->testcase as $testcase)
                <tr>
                    <td>{{ $testcase['name'] }}</td>
                    <td>{{ $testcase->failure ? 'Échec' : 'Succès' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
