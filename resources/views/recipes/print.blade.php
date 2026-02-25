<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $recipe->title }} - Recette</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; line-height: 1.6; }
        h1 { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .meta { display: flex; gap: 30px; margin: 20px 0; flex-wrap: wrap; }
        .meta-item { text-align: center; min-width: 100px; }
        .meta-item strong { display: block; font-size: 1.5rem; color: #333; }
        .section { margin: 30px 0; page-break-inside: avoid; }
        .section h2 { background: #333; color: white; padding: 10px; margin-bottom: 15px; }
        ul, ol { padding-left: 30px; }
        li { margin: 10px 0; }
        .no-print { margin-top: 40px; text-align: center; }
        button { padding: 10px 20px; margin: 0 10px; cursor: pointer; font-size: 1rem; }
        @media print {
            body { margin: 0; padding: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h1>{{ $recipe->title }}</h1>

    @if($recipe->short_description)
        <p style="font-style: italic; margin-bottom: 20px;">{{ $recipe->short_description }}</p>
    @endif

    <div class="meta">
        <div class="meta-item">
            <strong>{{ $recipe->prep_time ?? 0 }}</strong>
            <span>min préparation</span>
        </div>
        <div class="meta-item">
            <strong>{{ $recipe->cook_time ?? 0 }}</strong>
            <span>min cuisson</span>
        </div>
        <div class="meta-item">
            <strong>{{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }}</strong>
            <span>min total</span>
        </div>
        <div class="meta-item">
            <strong>{{ $recipe->servings ?? 1 }}</strong>
            <span>personne(s)</span>
        </div>
        <div class="meta-item">
            <strong>{{ $recipe->difficulty ?? 'N/A' }}</strong>
            <span>difficulté</span>
        </div>
    </div>

    <div class="section">
        <h2>🛒 Ingrédients</h2>
        <ul>
            @foreach($recipe->ingredients as $ingredient)
                <li>{{ $ingredient }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>👨‍🍳 Préparation</h2>
        <ol>
            @foreach($recipe->instructions as $instruction)
                <li>{{ $instruction }}</li>
            @endforeach
        </ol>
    </div>

    @if($recipe->description)
        <div class="section">
            <h2>📝 Notes et Conseils</h2>
            <div>{!! $recipe->description !!}</div>
        </div>
    @endif

    <div class="no-print">
        <button onclick="window.print()" style="background: #007bff; color: white; border: none;">
            🖨️ Imprimer
        </button>
        <button onclick="window.close()" style="background: #6c757d; color: white; border: none;">
            ❌ Fermer
        </button>
    </div>
</body>
</html>
