<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h1, p { text-align: center; }
    </style>
</head>
<body>
    <h1>Rapport des Tests Blopp_app_Laravel gorupe 7</h1>
    <p>Généré le : {{ now()->format('d/m/Y H:i:s') }}</p>

    <!-- Statistiques -->
    <h2>Statistiques</h2>
    <ul>
        <li>Total des tests : {{ $statistics['total'] }}</li>
        <li>Succès : {{ $statistics['success'] }}</li>
        <li>Échecs : {{ $statistics['failure'] }}</li>
        <li>Temps total des tests : {{ $statistics['totalTime'] }}</li>
    </ul>

    <!-- Tableau des résultats -->
    <table>
        <thead>
            <tr>
                <th>Test</th>
                <th>Statut</th>
                <th>Durée (s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tests as $test)
                <tr>
                    <td>{{ $test['name'] }}</td>
                    <td>{{ $test['status'] }}</td>
                    <td>{{ $test['time'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
