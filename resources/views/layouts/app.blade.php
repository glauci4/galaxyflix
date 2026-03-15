<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'GalaxyFlix') - Catálogo de Filmes</title>
    
    <!-- BODYSTYLE CSS via CDN -->
    <link rel="stylesheet" href="https://rawcdn.githack.com/FedeManzano/bodystyle/refs/heads/master/dist/css/bodystyle.min.css">
    
    <style>
        /* CORES GALÁCTICAS */
        :root {
            --galaxy-dark: #0B0C1E;        /* Azul escuro (fundo do espaço) */
            --galaxy-deep: #14152B;         /* Azul profundo */
            --galaxy-purple: #2D1B4A;       /* Roxo nebulosa */
            --galaxy-light-purple: #6B3F9C;  /* Roxo mais claro */
            --galaxy-blue: #1B2B4A;         /* Azul noturno */
            --galaxy-neon-blue: #4D7EFF;     /* Azul neon (destaque) */
            --galaxy-neon-purple: #B87CFF;   /* Roxo neon (destaque) */
            --galaxy-star: #FFE484;          /* Amarelo estrela */
        }
        
        /* FUNDO COM NEBULOSA */
        body {
            background-color: var(--galaxy-dark);
            color: white;
            min-height: 100vh;
            background-image: 
                /* Nebulosa roxa principal */
                radial-gradient(circle at 20% 30%, rgba(75, 30, 120, 0.4) 0%, transparent 40%),
                /* Nebulosa azul secundária */
                radial-gradient(circle at 80% 70%, rgba(30, 60, 150, 0.3) 0%, transparent 50%),
                /* Pontos de luz */
                radial-gradient(circle at 40% 80%, rgba(180, 130, 255, 0.2) 0%, transparent 30%),
                /* Fundo escuro base */
                linear-gradient(to bottom, #0B0C1E, #14152B);
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* ESTRELAS DECORATIVAS */
        .stars-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Não atrapalha cliques */
            z-index: 0;
        }
        
        /* Estrela amarela que pisca */
        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background-color: var(--galaxy-star);
            border-radius: 50%;
            animation: twinkle 2s infinite;
        }
        
        /* Estrela azul que pulsa */
        .star-blue {
            position: absolute;
            width: 4px;
            height: 4px;
            background-color: var(--galaxy-neon-blue);
            border-radius: 50%;
            animation: pulse-slow 4s infinite;
            box-shadow: 0 0 10px var(--galaxy-neon-blue);
        }
        
        /* Estrela cadente */
        .shooting-star {
            position: absolute;
            width: 2px;
            height: 2px;
            background-color: white;
            border-radius: 50%;
            animation: pulse 2s infinite;
            box-shadow: 0 0 10px 2px var(--galaxy-neon-purple);
        }
        
        /* CARD GALÁCTICO - efeito vidro fosco */
        .card-galaxy {
            background: linear-gradient(135deg, rgba(45, 27, 74, 0.3), rgba(27, 43, 74, 0.2));
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
        
        .card-galaxy:hover {
            transform: scale(1.03);
            box-shadow: 0 0 20px var(--galaxy-neon-purple);
        }
        
        /* BOTÃO PRINCIPAL - gradiente roxo/azul */
        .btn-galaxy {
            background: linear-gradient(135deg, var(--galaxy-purple), var(--galaxy-blue));
            color: white;
            font-weight: bold;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-galaxy:hover {
            background: linear-gradient(135deg, var(--galaxy-light-purple), var(--galaxy-neon-blue));
            transform: scale(1.05);
            box-shadow: 0 0 15px var(--galaxy-neon-purple);
        }
        
        /* BOTÃO SECUNDÁRIO - borda neon */
        .btn-galaxy-neon {
            background: transparent;
            color: var(--galaxy-neon-blue);
            font-weight: bold;
            padding: 10px 24px;
            border-radius: 8px;
            border: 2px solid var(--galaxy-neon-blue);
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-galaxy-neon:hover {
            background: var(--galaxy-neon-blue);
            color: white;
            box-shadow: 0 0 15px var(--galaxy-neon-blue);
        }
        
        /* BADGE DE NOTA */
        .nota-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(11, 12, 30, 0.8);
            backdrop-filter: blur(4px);
            border: 1px solid var(--galaxy-neon-purple);
            color: var(--galaxy-neon-purple);
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 0 10px var(--galaxy-neon-purple);
        }
        
        /* TÍTULO NEON - efeito brilhante */
        .titulo-neon {
            font-size: 36px;
            font-weight: bold;
            background: linear-gradient(135deg, var(--galaxy-neon-purple), var(--galaxy-neon-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 10px var(--galaxy-neon-purple);
        }
        
        /* TÍTULO MENOR */
        .titulo-galaxia {
            font-size: 24px;
            font-weight: bold;
            color: var(--galaxy-neon-blue);
            text-shadow: 0 0 5px var(--galaxy-neon-blue);
        }
        
        /* LINK GALÁCTICO */
        .link-galaxy {
            color: var(--galaxy-neon-blue);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .link-galaxy:hover {
            color: var(--galaxy-neon-purple);
            text-decoration: underline;
        }
        
        /* INPUTS ESTILIZADOS */
        .input-galaxy {
            width: 100%;
            padding: 12px 16px;
            background: rgba(20, 21, 43, 0.5);
            border: 1px solid var(--galaxy-light-purple);
            border-radius: 8px;
            color: white;
            transition: all 0.2s;
        }
        
        .input-galaxy:focus {
            border-color: var(--galaxy-neon-purple);
            box-shadow: 0 0 10px var(--galaxy-neon-purple);
            outline: none;
        }
        
        .input-galaxy::placeholder {
            color: var(--galaxy-light-purple);
            opacity: 0.5;
        }
        
        /* ANIMAÇÕES */
        @keyframes twinkle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 6s infinite;
        }
        
        /* CONTAINER COM EFEITO DE BRILHO */
        .glow-container {
            position: relative;
        }
        
        .glow-container::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            background: linear-gradient(135deg, var(--galaxy-neon-purple), var(--galaxy-neon-blue));
            border-radius: 12px;
            opacity: 0.2;
            z-index: -1;
            filter: blur(10px);
        }
    </style>
</head>
<body>
    <!-- ESTRELAS DECORATIVAS - criadas com PHP -->
    <div class="stars-container">
        <!-- Estrelas amarelas (30) -->
        @for($i = 0; $i < 30; $i++)
            <div class="star" style="top: {{ rand(0, 100) }}%; left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 5) }}s;"></div>
        @endfor
        <!-- Estrelas azuis (20) -->
        @for($i = 0; $i < 20; $i++)
            <div class="star-blue" style="top: {{ rand(0, 100) }}%; left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 3) }}s;"></div>
        @endfor
        <!-- Estrelas cadentes (5) -->
        @for($i = 0; $i < 5; $i++)
            <div class="shooting-star" style="top: {{ rand(0, 70) }}%; left: {{ rand(0, 70) }}%;"></div>
        @endfor
    </div>

    <!-- CONTEÚDO PRINCIPAL (fica acima das estrelas) -->
    <div style="position: relative; z-index: 10;">
        <!-- NAVBAR FIXA -->
        <nav style="background: linear-gradient(to bottom, rgba(20,21,43,0.9), transparent); 
                    backdrop-filter: blur(8px);
                    padding: 16px 24px;
                    position: fixed;
                    width: 100%;
                    top: 0;
                    z-index: 50;
                    border-bottom: 1px solid var(--galaxy-neon-purple);">
            <div style="max-width: 1280px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
                <!-- Logo com efeito neon -->
                <a href="/filmes" class="titulo-neon" style="font-size: 28px;">GALAXYFLIX</a>
                <!-- Espaço vazio para manter o layout -->
                <div style="display: flex; gap: 24px;"></div>
            </div>
        </nav>

        <!-- ESPAÇO PARA A NAVBAR NÃO SOBREPOR O CONTEÚDO -->
        <div style="padding-top: 80px;">
            <!-- Área onde o conteúdo das páginas será inserido -->
            @yield('content')
        </div>
    </div>
</body>
</html>