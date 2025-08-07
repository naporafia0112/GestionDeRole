<!DOCTYPE html>
<html>
<head>
    <title>Export des Graphiques</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .chart-box {
            page-break-inside: avoid;
            margin-bottom: 30px;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Graphiques</h2>

    @foreach($chartImages as $chart)
        <div class="chart-box">
            <p><strong>{{ $chart['id'] }}</strong></p>
            <img src="{{ $chart['image'] }}" alt="Graphique {{ $chart['id'] }}">
        </div>
    @endforeach

</body>
</html>
