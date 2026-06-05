<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Publicaciones</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #2b2323; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #fff7f7; color: #7a1515; font-weight: bold; border: 1px solid #f0dede; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { border: 1px solid #f0dede; padding: 6px; vertical-align: top; }
        .header { color: #9c1c1c; font-size: 18px; font-weight: bold; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">Producción Científica</div>
    <p>Listado exportado bajo los criterios seleccionados en el sistema.</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">N°</th>
                <th style="width: 45%">Título</th>
                <th style="width: 10%">Año</th>
                <th style="width: 15%">Ámbito</th>
                <th style="width: 25%">Autores</th>
                <th style="width: 25%">Pais</th>
                <th style="width: 25%">URL</th>
                <th style="width: 25%">Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($publications as $index => $publication)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $publication->title }}</strong></td>
                    <td>{{ $publication->publication_year ?? '—' }}</td>
                    <td>{{ $publication->scope ?? '—' }}</td>
                    <td>
                        @php
                            $authors = $publication->researchers->sortBy(fn($r) => $r->pivot->author_order ?? 999);
                        @endphp
                        {{ $authors->map(fn($r) => trim(implode(' ', array_filter([$r->name_1, $r->name_2, $r->last_name_1, $r->last_name_2]))))->filter()->implode(', ') ?: '—' }}
                    </td>
                    <td>{{ $publication->country_publication ?? '—' }}</td>
                    <td>{{ $publication->url ?? '—' }}</td>
                    <td>{{ $publication->type->type_name ?? '—' }}</td>
                    
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>