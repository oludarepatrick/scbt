<!DOCTYPE html>
<html>
<head>
    <title>View Result</title>
    <style>
        * { 
            font-family: "Segoe UI", Arial, Helvetica, sans-serif; 
        }

        body {
            background: linear-gradient(135deg, #f0f8ff, #e8f0ff);
            margin: 0;
            padding: 20px;
        }

        h3 {
            text-align: center;
            background: #4a76ff;
            color: white;
            width: fit-content;
            margin: 20px auto;
            padding: 12px 25px;
            border-radius: 8px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        }

        table {
            width: 1000px;
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 15px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        th {
            background: #4a76ff;
            color: white;
            padding: 10px;
            text-transform: uppercase;
            font-size: 13px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e6e6e6;
        }

        tr:nth-child(even) {
            background: #f7faff;
        }

        tr:hover {
            background: #eaf1ff;
        }

        #back-btn {
            margin: 25px auto;
            display: block;
            padding: 10px 20px;
            background: #4a76ff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            font-size: 15px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            transition: 0.2s;
        }

        #back-btn:hover {
            background: #3b5fcc;
        }

        @media print {
            #back-btn {
                display: none;
            }

            body {
                background: white;
            }

            table {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <button id="back-btn" onclick="history.back()">Go Back</button>

    <h3>
        Quiz Title: {{ $quizTitle }}  
        &nbsp; | &nbsp; Class: {{ $class }}  
        @if($arm !== 'optional')
            &nbsp; | &nbsp; Arm: {{ $arm }}
        @endif
    </h3>

    <table>
        <tr>
            <th>S/N</th>
            <th>Student Name</th>
            <th>Score</th>
            <th>Percentage</th>
        </tr>

        @foreach ($students as $index => $std)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $std['name'] }}</td>

                <td align="center">
                    {{ $std['score'] }} out of {{ $std['total'] }}
                </td>

                <td align="center">
                    {{ $std['percentage'] }}%
                </td>
            </tr>
        @endforeach

    </table>

    <button id="back-btn" onclick="history.back()">Go Back</button>

</body>
</html>
