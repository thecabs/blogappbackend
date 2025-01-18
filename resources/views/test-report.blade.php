<!DOCTYPE html>
<html>
<head>
    <style>
        /* Styles de base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        h1, p {
            text-align: center;
            margin: 20px 0;
        }
        h1 {
            color: #4CAF50;
            font-size: 2.5em;
        }
        p {
            font-size: 1.1em;
            color: #666;
        }

        /* Conteneur principal */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Liste des statistiques */
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin: 10px 0;
            font-size: 1.1em;
        }
        ul li span {
            font-weight: bold;
            color: #4CAF50;
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        /* Statut personnalisé */
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
        .failure {
            color: #E53935;
            font-weight: bold;
        }

        /* Pied de page */
        footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rapport des Tests - Blog_app_Laravel</h1>
        <p>Groupe 7 | Généré le : {{ now()->format('d/m/Y H:i:s') }}</p>

        <!-- Statistiques -->
        <h2>Statistiques</h2>
        <ul>
            <li>Total des tests : <span>{{ $statistics['total'] }}</span></li>
            <li>Succès : <span>{{ $statistics['success'] }}</span></li>
            <li>Échecs : <span>{{ $statistics['failure'] }}</span></li>
            <li>Temps total des tests : <span>{{ $statistics['totalTime'] }}</span></li>
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
                        <td class="{{ strtolower($test['status']) }}">
                            {{ $test['status'] }}
                        </td>
                        <td>{{ $test['time'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pied de page -->
    <footer>
        &copy; 2025 Blopp_app - Rapport de tests généré automatiquement.
    </footer>
</body>
</html>
