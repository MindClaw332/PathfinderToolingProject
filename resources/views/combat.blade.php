<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pathfinder 2e Combat Manager</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <!-- Font Awesome Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Custom Combat Manager Styles - Using App Theme Colors */
        :root {
            --color-primary: #000917;
            --color-secondary: #00162E;
            --color-tertiary: #002445;
            --color-accent: #AE8708;
            --color-text: #1b1b18;
            --color-bg: #FDFDFC;
            --color-card: #ffffff;
            --color-dark-text: #EDEDEC;
            --color-dark-bg: #0a0a0a;
            --color-dark-card: #161615;
            --dice-roll: #4a2b0d;
        }
        
        /* Apply theme colors directly using CSS custom properties */
        .combat-manager {
            --primary: var(--color-primary);
            --secondary: var(--color-secondary);
            --tertiary: var(--color-tertiary);
            --accent: var(--color-accent);
        }
        
        /* Fix for white edges */
        html, body {
            margin: 0;
            padding: 0;
            background: var(--color-bg);
            min-height: 100vh;
            font-family: 'Instrument Sans', sans-serif;
            overflow-x: hidden;
        }
        
        .dark body {
            background: var(--color-dark-bg);
        }
        
        .combat-manager {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
            color: var(--color-text);
            background-color: var(--color-bg);
            min-height: 100vh;
        }
        
        .dark .combat-manager {
            color: var(--color-dark-text);
            background-color: var(--color-dark-bg);
        }
        
        .header {
            background: var(--color-secondary);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 9, 23, 0.3);
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .dice-header {
            position: relative;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }
        
        .dice-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--color-accent);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .dice-btn:hover {
            transform: rotate(15deg) scale(1.1);
            box-shadow: 0 0 10px rgba(174, 135, 8, 0.5);
        }
        
        .dice-result {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: var(--dice-roll);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            font-size: 4rem;
            font-weight: bold;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 200px;
            text-align: center;
            transition: transform 0.3s ease-out;
        }
        
        .dice-result.visible {
            transform: translate(-50%, -50%) scale(1);
        }
        
        .dice-label {
            font-size: 1.2rem;
            margin-top: 1rem;
            opacity: 0.8;
        }
        
        .dice-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 999;
            display: none;
        }
        
        .dice-overlay.visible {
            display: block;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: var(--color-accent);
            color: var(--color-primary);
            border: none;
        }
        
        .btn-primary:hover {
            background: #c59a0b;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(174, 135, 8, 0.3);
        }
        
        .btn-secondary {
            background: var(--color-tertiary);
            color: white;
            border: none;
        }
        
        .btn-secondary:hover {
            background: #003a6e;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 36, 69, 0.3);
        }

        .btn-info {
            background: rgba(0, 36, 69, 0.2);
            color: white;
            border: none;
        }
        
        .btn-info:hover {
            background: rgba(0, 36, 69, 0.3);
            transform: translateY(-1px);
        }
        
        .turn-order {
            display: flex;
            overflow-x: auto;
            gap: 0.5rem;
            padding: 0.5rem 0;
            margin-bottom: 1.5rem;
        }
        
        .turn-item {
            min-width: 120px;
            padding: 0.75rem;
            border-radius: 6px;
            background: var(--color-card);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .dark .turn-item {
            background: var(--color-dark-card);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .turn-item.active {
            background: var(--color-accent);
            color: var(--color-primary);
            font-weight: 600;
        }
        
        .turn-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* New grid layout for combatants */
        .combatants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .combatant-card {
            background: var(--color-card);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            scroll-margin-top: 100px; /* Space for sticky header */
        }
        
        .dark .combatant-card {
            background: var(--color-dark-card);
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        
        .combatant-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .combatant-card.selected {
            border-left: 4px solid var(--color-accent);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(174, 135, 8, 0.4);
        }
        
        .combatant-card.active-turn {
            border: 2px solid var(--color-accent);
            background: linear-gradient(135deg, var(--color-card) 0%, rgba(174, 135, 8, 0.08) 100%);
            box-shadow: 0 4px 12px rgba(174, 135, 8, 0.25);
        }
        
        .dark .combatant-card.active-turn {
            background: linear-gradient(135deg, var(--color-dark-card) 0%, rgba(174, 135, 8, 0.12) 100%);
        }
        
        .combatant-card.monster .tag {
            background: rgba(174, 135, 8, 0.15);
            color: var(--color-accent);
            font-weight: 600;
        }
        
        .combatant-card.player .tag {
            background: rgba(0, 36, 69, 0.15);
            color: var(--color-tertiary);
            font-weight: 600;
        }
        
        .dark .combatant-card.player .tag {
            color: #4d9cff;
        }
        
        .stat-block {
            background: rgba(0, 22, 46, 0.06);
            border: 1px solid rgba(0, 22, 46, 0.1);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .dark .stat-block {
            background: rgba(0, 22, 46, 0.2);
            border-color: rgba(0, 22, 46, 0.3);
        }
        
        .hp-controls {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .hp-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            color: var(--color-text);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .hp-btn:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
        }
        
        .dark .hp-btn {
            background: rgba(255, 255, 255, 0.2);
            color: var(--color-dark-text);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .dark .hp-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Improved HP input styling for dark mode */
        .dark .combatant-card input[type="number"] {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .detail-header {
            background: var(--color-secondary);
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
        }
        
        .detail-content {
            background: var(--color-card);
            border-radius: 0 0 8px 8px;
            padding: 1.5rem;
        }
        
        .dark .detail-content {
            background: var(--color-dark-card);
        }
        
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            background: rgba(0, 0, 0, 0.03);
            border-radius: 6px;
            padding: 0.75rem;
            text-align: center;
        }
        
        .dark .stat-item {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .dark .stat-label {
            color: #aaa;
        }
        
        .stat-value {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .attack {
            background: rgba(0, 0, 0, 0.03);
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            position: relative;
        }
        
        .dark .attack {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .roll-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: var(--color-accent);
            color: var(--color-primary);
            border: none;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .roll-btn:hover {
            background: #c59a0b;
            transform: translateY(-2px);
        }
        
        .save-rolls {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
        }
        
        .dark .save-rolls {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .save-rolls h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .save-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .save-btn {
            flex: 1;
            min-width: 80px;
            padding: 0.5rem;
            border-radius: 4px;
            background: var(--color-tertiary);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }
        
        .save-btn:hover {
            background: #003a6e;
            transform: translateY(-2px);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .combatants-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .header-buttons {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }
            
            .header-buttons .btn-group {
                flex-wrap: wrap;
            }
            
            .dice-header {
                position: static;
                margin-top: 1rem;
                justify-content: center;
                width: 100%;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        .combatants-section {
            position: relative;
        }
        
        .section-header {
            position: sticky;
            top: 0;
            background: var(--color-bg);
            z-index: 10;
            padding: 1rem 0;
            border-bottom: 2px solid rgba(0, 22, 46, 0.1);
            margin-bottom: 1rem;
        }
        
        .dark .section-header {
            background: var(--color-dark-bg);
            border-bottom-color: rgba(174, 135, 8, 0.2);
        }
        
        /* Theme Toggle Styles */
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            border-radius: 15px;
            background: var(--color-tertiary);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
            margin-left: 0.5rem;
        }
        
        .theme-toggle::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--color-accent);
            transition: transform 0.3s ease;
        }
        
        .dark .theme-toggle::before {
            transform: translateX(30px);
        }
        
        .theme-toggle i {
            position: absolute;
            font-size: 14px;
            color: white;
            top: 8px;
        }
        
        .theme-toggle .sun {
            left: 7px;
        }
        
        .theme-toggle .moon {
            right: 7px;
        }
        
        /* Scroll to info button */
        .scroll-to-info {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--color-accent);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }
        
        .scroll-to-info:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
        }
        
        .scroll-to-info.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        /* Conditions styling */
        .conditions-section {
            margin-top: 1rem;
        }
        
        .conditions-label {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            display: block;
        }
        
        .conditions-input {
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 0.875rem;
        }
        
        .dark .conditions-input {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        /* Initiative roll button */
        .initiative-roll-btn {
            background: var(--color-tertiary);
            color: white;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 0.5rem;
        }
        
        .initiative-roll-btn:hover {
            background: #003a6e;
            transform: translateY(-1px);
        }
        
        /* Combatant initiative container */
        .initiative-container {
            display: flex;
            align-items: center;
        }
        
        /* Dice icon improvements */
        .dice-btn i {
            font-size: 1.2rem;
        }
        
        .dice-header {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        /* Dice tooltips */
        .dice-btn {
            position: relative;
        }
        
        .dice-btn::after {
            content: attr(title);
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .dice-btn:hover::after {
            opacity: 1;
        }
        

        

        
        /* Combat tracker header */
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
        }
        
        .header-title {
            font-size: 1.75rem;
            margin-right: 1rem;
        }
        
        .header-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .header-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Dice roller animation */
        @keyframes diceRoll {
            0% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(90deg) scale(1.2); }
            50% { transform: rotate(180deg) scale(1.3); }
            75% { transform: rotate(270deg) scale(1.2); }
            100% { transform: rotate(360deg) scale(1); }
        }
        
        .dice-btn.rolling {
            animation: diceRoll 0.5s ease-in-out;
        }
        
        /* Dice results styling */
        .critical-success {
            color: #4ade80;
            text-shadow: 0 0 10px rgba(74, 222, 128, 0.7);
        }
        
        .critical-failure {
            color: #f87171;
            text-shadow: 0 0 10px rgba(248, 113, 113, 0.7);
        }
        
        /* Combatant card enhancements */
        .combatant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .combatant-type {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 700;
        }
        
        .player-type {
            background-color: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }
        
        .monster-type {
            background-color: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }
        
        .active-indicator {
            background-color: #f59e0b;
            color: #7c2d12;
            padding: 0.15rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        
        /* Custom Dice Roller Styles */
        .custom-dice-roller {
            margin-top: 1rem;
            background: rgba(0, 0, 0, 0.1);
            padding: 1rem;
            border-radius: 8px;
            width: 100%;
        }
        
        .dark .custom-dice-roller {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .custom-dice-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .custom-dice-input {
            flex: 1;
            min-width: 150px;
            height: 40px;
            padding: 0 10px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.8);
            color: var(--color-text);
        }
        
        .dark .custom-dice-input {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .dice-history {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .dark .dice-history {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .history-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .dark .history-item {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .history-expression {
            font-weight: 600;
        }
        
        .history-result {
            font-weight: 700;
            color: var(--color-accent);
        }
        
        .history-details {
            font-size: 0.8rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="combat-manager" x-data="combatManager">
        <!-- Dice roller overlay -->
        <div class="dice-overlay" :class="{ 'visible': showDiceResult }" @click="showDiceResult = false"></div>
        
        <!-- Dice result display -->
        <div class="dice-result" :class="{ 'visible': showDiceResult }">
            <div :class="{ 
                    'critical-success': diceCritical === 'success', 
                    'critical-failure': diceCritical === 'failure' 
                }" 
                x-text="diceResult">
            </div>
            <div class="dice-label" x-text="diceLabel"></div>
        </div>
        
        <!-- Scroll to info button -->
        <div 
            class="scroll-to-info" 
            @click="scrollToDetails"
            :class="{ 'hidden': !selectedCombatant }"
            title="Scroll to Character Info"
        >
            <i class="fas fa-arrow-down"></i>
        </div>
        
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1 class="header-title">Pathfinder 2e Combat Manager</h1>
                
                <div class="header-controls">
                    <div class="header-buttons">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Add Combatant
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-stop mr-1"></i> End Combat
                        </button>
                    </div>
                    
                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme" 
                            class="theme-toggle" 
                            title="Toggle theme">
                        <i class="sun">☀️</i>
                        <i class="moon">🌙</i>
                    </button>
                </div>
            </div>
            
            <!-- Dice buttons with free icons -->
            <div class="dice-header">
                <div class="dice-btn" @click="rollD20" title="Roll d20">
                    <span>d20</span>
                </div>
                <div class="dice-btn" @click="rollDice('d4')" title="Roll d4">
                    <span>d4</span>
                </div>
                <div class="dice-btn" @click="rollDice('d6')" title="Roll d6">
                    <span>d6</span>
                </div>
                <div class="dice-btn" @click="rollDice('d8')" title="Roll d8">
                    <span>d8</span>
                </div>
                <div class="dice-btn" @click="rollDice('d10')" title="Roll d10">
                    <span>d10</span>
                </div>
                <div class="dice-btn" @click="rollDice('d12')" title="Roll d12">
                    <span>d12</span>
                </div>
                <div class="dice-btn" @click="rollDice('d100')" title="Roll d100 (percentile)">
                    <span>%</span>
                </div>
            </div>
            
            <!-- Custom Dice Roller -->
            <div class="custom-dice-roller">
                <h4 class="font-bold mb-2">Custom Dice Roller</h4>
                <div class="custom-dice-controls">
                    <input 
                        type="text" 
                        x-model="customDiceExpression" 
                        placeholder="e.g. 3d6+2" 
                        class="custom-dice-input"
                        @keyup.enter="rollCustomDice"
                    >
                    <button class="btn btn-secondary" @click="rollCustomDice">
                        <i class="fas fa-dice-d20 mr-1"></i> Roll
                    </button>
                    <button class="btn btn-info" @click="clearDiceHistory">
                        <i class="fas fa-trash-alt mr-1"></i> Clear History
                    </button>
                </div>
                <div class="text-xs mt-1 text-gray-400 dark:text-gray-300">
                    Enter dice expression (e.g. 2d8+5, 4d6, d20+3)
                </div>
                
                <!-- Dice History -->
                <div class="dice-history" x-show="diceHistory.length > 0">
                    <h5 class="font-bold mb-2">Roll History</h5>
                    <div class="space-y-2">
                        <template x-for="(entry, index) in diceHistory" :key="index">
                            <div class="history-item">
                                <div>
                                    <div class="history-expression" x-text="entry.expression"></div>
                                    <div class="history-details" 
                                         x-text="'Rolls: ' + entry.rolls.join(', ') + (entry.modifier ? ' + ' + entry.modifier : '')">
                                    </div>
                                </div>
                                <div class="history-result" x-text="entry.result"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Turn Order -->
        <div class="turn-order">
            <template x-for="(combatant, index) in combatants" :key="combatant.id">
                <div 
                    @click="setCurrentTurnAndScroll(index, combatant)"
                    :class="{
                        'turn-item': true,
                        'active': combatant.active
                    }"
                >
                    <div x-text="combatant.name" class="font-medium"></div>
                    <div x-text="'(' + combatant.initiative + ')'" class="text-sm"></div>
                </div>
            </template>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Combatant List -->
            <div class="lg:col-span-2 combatants-section">
                <div class="section-header">
                    <h2 class="text-xl font-bold">Combatants</h2>
                </div>
                
                <div class="combatants-grid">
                    <template x-for="combatant in combatants" :key="combatant.id">
                        <div 
                            @click="selectCombatant(combatant)"
                            :class="{
                                'combatant-card': true,
                                'monster': combatant.type === 'monster',
                                'player': combatant.type === 'player',
                                'selected': selectedCombatant && selectedCombatant.id === combatant.id,
                                'active-turn': combatant.active
                            }"
                            :id="'combatant-' + combatant.id"
                        >
                            <div class="combatant-header">
                                <h3 class="font-bold text-lg" x-text="combatant.name"></h3>
                                <div class="flex items-center gap-2">
                                    <span class="combatant-type" 
                                          :class="combatant.type === 'monster' ? 'monster-type' : 'player-type'"
                                          x-text="combatant.type === 'monster' ? 'MONSTER' : 'PLAYER'">
                                    </span>
                                    <template x-if="combatant.active">
                                        <span class="active-indicator">ACTIVE</span>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                <div class="initiative-container">
                                    <span>Initiative: </span>
                                    <span x-text="combatant.initiative" class="font-medium ml-1"></span>
                                    <!-- Initiative Roll Button -->
                                    <button 
                                        @click.stop="rollInitiative(combatant)"
                                        class="initiative-roll-btn"
                                        title="Roll Initiative"
                                    >
                                        <i class="fas fa-dice"></i>
                                    </button>
                                </div>
                                <div>AC: <span x-text="combatant.ac" class="font-medium"></span></div>
                                <template x-if="combatant.class">
                                    <div>Class: <span x-text="combatant.class" class="font-medium"></span></div>
                                </template>
                                <template x-if="combatant.level">
                                    <div>Level: <span x-text="combatant.level" class="font-medium"></span></div>
                                </template>
                            </div>
                            
                            <div class="mt-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">HP:</span>
                                    <div class="flex items-center">
                                        <input 
                                            type="number" 
                                            x-model="combatant.currentHp" 
                                            class="w-16 text-center border rounded py-1 px-2 text-sm"
                                            min="0"
                                            :max="combatant.maxHp"
                                        >
                                        <span class="mx-1 text-sm">/</span>
                                        <span x-text="combatant.maxHp" class="text-sm"></span>
                                    </div>
                                </div>
                                
                                <div class="hp-controls">
                                    <button 
                                        @click.stop="adjustHP(combatant, -1)"
                                        class="hp-btn text-xs"
                                    >
                                        -1
                                    </button>
                                    <button 
                                        @click.stop="adjustHP(combatant, -5)"
                                        class="hp-btn text-xs"
                                    >
                                        -5
                                    </button>
                                    <button 
                                        @click.stop="adjustHP(combatant, 5)"
                                        class="hp-btn text-xs"
                                    >
                                        +5
                                    </button>
                                    <button 
                                        @click.stop="adjustHP(combatant, 1)"
                                        class="hp-btn text-xs"
                                    >
                                        +1
                                    </button>
                                </div>
                                
                                <!-- Conditions Input -->
                                <div class="conditions-section">
                                    <label class="conditions-label">Conditions:</label>
                                    <input 
                                        type="text" 
                                        x-model="combatant.conditions" 
                                        class="conditions-input" 
                                        placeholder="Add conditions (comma separated)"
                                    >
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Combatant Detail -->
            <div class="lg:col-span-1" id="character-info-section">
                <h2 class="text-xl font-bold mb-3">Combatant Details</h2>
                
                <template x-if="selectedCombatant">
                    <div>
                        <div class="detail-header">
                            <h3 class="text-xl font-bold" x-text="selectedCombatant.name"></h3>
                            <div class="flex flex-col gap-1 mt-2 text-sm">
                                <div>AC: <span x-text="selectedCombatant.ac" class="font-bold"></span></div>
                                <div>HP: 
                                    <span x-text="selectedCombatant.currentHp" class="font-bold"></span>
                                    <span>/</span>
                                    <span x-text="selectedCombatant.maxHp" class="font-bold"></span>
                                </div>
                                <div>Initiative: <span x-text="selectedCombatant.initiative" class="font-bold"></span></div>
                                <div>Conditions: 
                                    <span class="font-bold" x-text="selectedCombatant.conditions || 'None'"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-content">
                            <!-- Conditions Editor -->
                            <div class="mb-6">
                                <h4 class="font-bold text-lg mb-2">Edit Conditions</h4>
                                <textarea 
                                    x-model="selectedCombatant.conditions" 
                                    class="w-full p-2 border rounded conditions-textarea" 
                                    placeholder="Enter conditions (comma separated)&#10;Example: frightened 2, sickened, slowed"
                                ></textarea>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Separate multiple conditions with commas
                                </div>
                            </div>
                            
                            <!-- Stats Grid -->
                            <div class="stat-grid">
                                <div class="stat-item">
                                    <div class="stat-label">Speed</div>
                                    <div class="stat-value" x-text="selectedCombatant.speed"></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Perception</div>
                                    <div class="stat-value" x-text="selectedCombatant.perception"></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Saves</div>
                                    <div class="stat-value" x-text="selectedCombatant.saves"></div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Actions</div>
                                    <div class="stat-value" x-text="selectedCombatant.actions"></div>
                                </div>
                            </div>
                            
                            <!-- Stat Block -->
                            <template x-if="selectedCombatant.type === 'monster'">
                                <div>
                                    <div class="stat-block">
                                        <h4 class="font-bold text-lg mb-3">Special Abilities</h4>
                                        
                                        <div class="mb-4 text-sm">
                                            <strong>Immunities:</strong> acid, clumsy, disease, drained, enfeebled, 
                                            mental, paralyzed, persistent damage, petrified, poison, polymorph, stunned
                                        </div>
                                        
                                        <div class="mb-4 text-sm">
                                            <strong>Resistances:</strong> fire 20, physical 15 (except adamantine)
                                        </div>
                                        
                                        <div class="mb-4 text-sm">
                                            <strong>Regeneration:</strong> 50 HP (deactivated by acid damage)
                                        </div>
                                        
                                        <h4 class="font-bold text-lg mb-3">Attacks</h4>
                                        <div class="space-y-3">
                                            <div class="attack">
                                                <div class="font-bold">Jaws</div>
                                                <div class="text-sm">+35 (magical, reach 20 ft), Damage 4d12+25 piercing</div>
                                                <button class="roll-btn" @click="rollAttack('Jaws', 35, '4d12+25')">Roll</button>
                                            </div>
                                            <div class="attack">
                                                <div class="font-bold">Claw</div>
                                                <div class="text-sm">+35 (agile, magical, reach 15 ft), Damage 4d10+25 slashing</div>
                                                <button class="roll-btn" @click="rollAttack('Claw', 35, '4d10+25')">Roll</button>
                                            </div>
                                            <div class="attack">
                                                <div class="font-bold">Tail</div>
                                                <div class="text-sm">+33 (magical, reach 30 ft), Damage 4d8+25 bludgeoning</div>
                                                <button class="roll-btn" @click="rollAttack('Tail', 33, '4d8+25')">Roll</button>
                                            </div>
                                            <div class="attack">
                                                <div class="font-bold">Spine Volley</div>
                                                <div class="text-sm">(2 actions) 120 ft cone, 8d6 piercing, DC 43 Reflex</div>
                                                <button class="roll-btn" @click="rollDice('8d6', 'Spine Volley Damage')">Roll Dmg</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Saving Throw Roller -->
                                    <div class="save-rolls">
                                        <h4 class="font-bold text-lg mb-2">
                                            <i class="fas fa-shield-alt"></i> Saving Throws
                                        </h4>
                                        <div class="save-buttons">
                                            <button class="save-btn" @click="rollSave('Fortitude', selectedCombatant.fortSave)">
                                                Fortitude <span x-text="selectedCombatant.fortSave"></span>
                                            </button>
                                            <button class="save-btn" @click="rollSave('Reflex', selectedCombatant.refSave)">
                                                Reflex <span x-text="selectedCombatant.refSave"></span>
                                            </button>
                                            <button class="save-btn" @click="rollSave('Will', selectedCombatant.willSave)">
                                                Will <span x-text="selectedCombatant.willSave"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="selectedCombatant.type === 'player'">
                                <div class="stat-block">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <div class="mb-2 text-sm">
                                                <strong>Class:</strong> <span x-text="selectedCombatant.class"></span>
                                            </div>
                                            <div class="mb-2 text-sm">
                                                <strong>Level:</strong> <span x-text="selectedCombatant.level"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Player Attack Roller -->
                                    <div class="mt-4">
                                        <h4 class="font-bold text-lg mb-2">Attack Roller</h4>
                                        <div class="flex gap-2">
                                            <button class="btn btn-primary flex-1" @click="rollD20">
                                                Roll d20
                                            </button>
                                            <button class="btn btn-secondary flex-1" @click="rollDice('2d6')">
                                                Roll 2d6
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                
                <template x-if="!selectedCombatant">
                    <div class="detail-content text-center py-12">
                        <div class="text-gray-500 dark:text-gray-400">
                            Select a combatant to view details
                        </div>
                        <div class="mt-4 text-5xl text-gray-300 dark:text-gray-600">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('combatManager', () => ({
                combatants: [],
                selectedCombatant: null,
                theme: 'dark', // Default theme
                showDiceResult: false,
                diceResult: 0,
                diceLabel: "",
                diceCritical: null, // 'success' or 'failure'
                customDiceExpression: "", // For custom dice input
                diceHistory: [], // To store roll history
                
                init() {
                    // Initialize theme
                    const savedTheme = localStorage.getItem('theme');
                    this.theme = savedTheme || 'dark';
                    document.documentElement.className = this.theme;
                    
                    // Initialize with example combatants
                    this.combatants = [
                        {
                            id: 1,
                            name: 'Tarrasque',
                            type: 'monster',
                            ac: 54,
                            maxHp: 540,
                            currentHp: 540,
                            initiative: 30,
                            initiativeBonus: 35, // Perception bonus
                            speed: '40 ft',
                            perception: '+35',
                            saves: 'Fort +38, Ref +32, Will +34',
                            fortSave: 38,
                            refSave: 32,
                            willSave: 34,
                            actions: '3',
                            active: true,
                            conditions: 'Frightened 2, Slowed'
                        },
                        {
                            id: 2,
                            name: 'Valeros',
                            type: 'player',
                            ac: 28,
                            maxHp: 120,
                            currentHp: 98,
                            initiative: 20,
                            initiativeBonus: 18, // Perception bonus
                            speed: '25 ft',
                            class: 'Fighter',
                            level: 12,
                            perception: '+18',
                            saves: 'Fort +22, Ref +16, Will +14',
                            actions: '3',
                            active: false,
                            conditions: 'Wounded 1'
                        },
                        {
                            id: 3,
                            name: 'Kyra',
                            type: 'player',
                            ac: 26,
                            maxHp: 110,
                            currentHp: 85,
                            initiative: 18,
                            initiativeBonus: 20, // Perception bonus
                            speed: '25 ft',
                            class: 'Cleric',
                            level: 12,
                            perception: '+20',
                            saves: 'Fort +20, Ref +14, Will +22',
                            actions: '3',
                            active: false,
                            conditions: ''
                        },
                        {
                            id: 4,
                            name: 'Ezren',
                            type: 'player',
                            ac: 24,
                            maxHp: 95,
                            currentHp: 72,
                            initiative: 17,
                            initiativeBonus: 16, // Perception bonus
                            speed: '25 ft',
                            class: 'Wizard',
                            level: 12,
                            perception: '+16',
                            saves: 'Fort +16, Ref +16, Will +20',
                            actions: '3',
                            active: false,
                            conditions: 'Drained 1'
                        },
                        {
                            id: 5,
                            name: 'Merisiel',
                            type: 'player',
                            ac: 30,
                            maxHp: 105,
                            currentHp: 92,
                            initiative: 12,
                            initiativeBonus: 18, // Perception bonus
                            speed: '35 ft',
                            class: 'Rogue',
                            level: 12,
                            perception: '+18',
                            saves: 'Fort +16, Ref +22, Will +16',
                            actions: '3',
                            active: false,
                            conditions: 'Clumsy 1'
                        }
                    ];
                    
                    // Sort by initiative (highest first)
                    this.combatants.sort((a, b) => b.initiative - a.initiative);
                    
                    // Select first combatant by default
                    this.selectedCombatant = this.combatants[0];
                    
                    // Initialize dice history
                    this.diceHistory = [];
                },
                
                // Toggle theme between dark and light
                toggleTheme() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    document.documentElement.className = this.theme;
                    localStorage.setItem('theme', this.theme);
                },
                
                selectCombatant(combatant) {
                    this.selectedCombatant = combatant;
                },
                
                setCurrentTurn(index) {
                    // Remove active status from all combatants
                    this.combatants.forEach(c => c.active = false);
                    
                    // Set selected combatant as active
                    this.combatants[index].active = true;
                    
                    // Sort to keep initiative order
                    this.combatants.sort((a, b) => b.initiative - a.initiative);
                    
                    // Re-find the active combatant index after sort
                    const activeIndex = this.combatants.findIndex(c => c.active);
                    
                    // If we have an active combatant, ensure it's selected
                    if (activeIndex !== -1) {
                        this.selectedCombatant = this.combatants[activeIndex];
                    }
                },
                
                setCurrentTurnAndScroll(index, combatant) {
                    // Set current turn
                    this.setCurrentTurn(index);
                    
                    // Select the combatant
                    this.selectCombatant(combatant);
                    
                    // Scroll to the combatant card
                    this.$nextTick(() => {
                        const element = document.getElementById('combatant-' + combatant.id);
                        if (element) {
                            element.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center',
                                inline: 'nearest'
                            });
                        }
                    });
                },
                
                adjustHP(combatant, amount) {
                    combatant.currentHp += amount;
                    
                    // Ensure HP doesn't go below 0 or above max
                    if (combatant.currentHp < 0) combatant.currentHp = 0;
                    if (combatant.currentHp > combatant.maxHp) combatant.currentHp = combatant.maxHp;
                    
                    // Update selected combatant if it's this one
                    if (this.selectedCombatant && this.selectedCombatant.id === combatant.id) {
                        this.selectedCombatant = {...combatant};
                    }
                },
                
                // Scroll to character info section
                scrollToDetails() {
                    const element = document.getElementById('character-info-section');
                    if (element) {
                        element.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start'
                        });
                    }
                },
                
                // Dice roller functions
                rollD20() {
                    // Add rolling animation
                    const d20Btn = document.querySelector('[title="Roll d20"]');
                    if (d20Btn) {
                        d20Btn.classList.add('rolling');
                        setTimeout(() => d20Btn.classList.remove('rolling'), 500);
                    }
                    
                    const roll = Math.floor(Math.random() * 20) + 1;
                    this.showDiceResult = true;
                    this.diceResult = roll;
                    this.diceLabel = "d20 Roll";
                    
                    // Check for critical success/failure
                    this.diceCritical = null;
                    if (roll === 20) this.diceCritical = 'success';
                    if (roll === 1) this.diceCritical = 'failure';
                    
                    // Auto hide after 2 seconds
                    setTimeout(() => {
                        this.showDiceResult = false;
                    }, 2000);
                },
                
                rollDice(diceType, label = "") {
                    // Add rolling animation
                    const btn = document.querySelector(`[title="Roll ${diceType}"]`);
                    if (btn) {
                        btn.classList.add('rolling');
                        setTimeout(() => btn.classList.remove('rolling'), 500);
                    }
                    
                    let result = 0;
                    let labelText = label || diceType + " Roll";
                    
                    if (diceType.includes('d')) {
                        // Handle dice expressions like "2d6" or "4d8+5"
                        const parts = diceType.split('d');
                        const numDice = parseInt(parts[0]) || 1;
                        const diceSize = parseInt(parts[1]);
                        
                        // Special case for d100 (percentile)
                        if (diceSize === 100) {
                            result = Math.floor(Math.random() * 100) + 1;
                        } else {
                            for (let i = 0; i < numDice; i++) {
                                result += Math.floor(Math.random() * diceSize) + 1;
                            }
                            
                            // Check for modifiers (like +5 in "2d6+5")
                            if (diceType.includes('+')) {
                                const modifier = parseInt(diceType.split('+')[1]);
                                if (!isNaN(modifier)) {
                                    result += modifier;
                                    labelText = diceType + " Roll";
                                }
                            }
                        }
                    } else {
                        // Handle single dice rolls
                        result = Math.floor(Math.random() * parseInt(diceType)) + 1;
                    }
                    
                    this.showDiceResult = true;
                    this.diceResult = result;
                    this.diceLabel = labelText;
                    this.diceCritical = null;
                    
                    // Auto hide after 2 seconds
                    setTimeout(() => {
                        this.showDiceResult = false;
                    }, 2000);
                },
                
                // Roll initiative for a combatant
                rollInitiative(combatant) {
                    const roll = Math.floor(Math.random() * 20) + 1;
                    const bonus = combatant.initiativeBonus || 0;
                    const total = roll + bonus;
                    
                    combatant.initiative = total;
                    
                    // Update UI
                    this.$nextTick(() => {
                        // Re-sort combatants by initiative
                        this.combatants.sort((a, b) => b.initiative - a.initiative);
                        
                        // Show roll result
                        this.diceResult = total;
                        this.diceLabel = `${combatant.name} Initiative`;
                        this.diceCritical = null;
                        this.showDiceResult = true;
                        
                        // Auto hide after 2 seconds
                        setTimeout(() => {
                            this.showDiceResult = false;
                        }, 2000);
                    });
                },
                
                // Roll attack for monsters
                rollAttack(attackName, attackBonus, damageDice) {
                    const attackRoll = Math.floor(Math.random() * 20) + 1;
                    const totalAttack = attackRoll + attackBonus;
                    
                    let damage = 0;
                    if (damageDice) {
                        // Calculate damage
                        const parts = damageDice.split('d');
                        const numDice = parseInt(parts[0]) || 1;
                        const diceSize = parseInt(parts[1]);
                        
                        for (let i = 0; i < numDice; i++) {
                            damage += Math.floor(Math.random() * diceSize) + 1;
                        }
                        
                        // Check for modifiers (like +5 in "2d6+5")
                        if (damageDice.includes('+')) {
                            const modifier = parseInt(damageDice.split('+')[1]);
                            if (!isNaN(modifier)) {
                                damage += modifier;
                            }
                        }
                    }
                    
                    let resultText = `${attackName} Attack:\n`;
                    resultText += `Attack: ${attackRoll} + ${attackBonus} = ${totalAttack}\n`;
                    
                    if (damageDice) {
                        resultText += `Damage: ${damageDice} = ${damage}`;
                    }
                    
                    this.showDiceResult = true;
                    this.diceResult = totalAttack;
                    this.diceLabel = `${attackName} Attack`;
                    
                    // Check for critical success/failure
                    this.diceCritical = null;
                    if (attackRoll === 20) this.diceCritical = 'success';
                    if (attackRoll === 1) this.diceCritical = 'failure';
                    
                    // Create a more detailed alert for the full result
                    setTimeout(() => {
                        alert(resultText);
                    }, 500);
                    
                    // Auto hide the dice display after 2 seconds
                    setTimeout(() => {
                        this.showDiceResult = false;
                    }, 2000);
                },
                
                // Roll saving throw
                rollSave(saveType, saveBonus) {
                    const saveRoll = Math.floor(Math.random() * 20) + 1;
                    const totalSave = saveRoll + saveBonus;
                    
                    this.showDiceResult = true;
                    this.diceResult = totalSave;
                    this.diceLabel = `${saveType} Save`;
                    
                    // Check for critical success/failure
                    this.diceCritical = null;
                    if (saveRoll === 20) this.diceCritical = 'success';
                    if (saveRoll === 1) this.diceCritical = 'failure';
                    
                    // Auto hide after 2 seconds
                    setTimeout(() => {
                        this.showDiceResult = false;
                    }, 2000);
                },
                
                // Roll custom dice expression
                rollCustomDice() {
                    if (!this.customDiceExpression) return;
                    
                    try {
                        // Parse the dice expression
                        const result = this.parseDiceExpression(this.customDiceExpression);
                        
                        // Add to history
                        this.diceHistory.unshift({
                            expression: this.customDiceExpression,
                            result: result.total,
                            rolls: result.rolls,
                            modifier: result.modifier
                        });
                        
                        // Keep only last 10 history items
                        if (this.diceHistory.length > 10) {
                            this.diceHistory.pop();
                        }
                        
                        // Show result
                        this.showDiceResult = true;
                        this.diceResult = result.total;
                        this.diceLabel = "Custom Roll: " + this.customDiceExpression;
                        this.diceCritical = null;
                        
                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            this.showDiceResult = false;
                        }, 3000);
                    } catch (error) {
                        alert("Invalid dice expression: " + error.message);
                    }
                },
                
                // Parse dice expressions like "3d6+2"
                parseDiceExpression(expression) {
                    // Clean the expression: remove spaces and convert to lowercase
                    const cleaned = expression.toLowerCase().replace(/\s+/g, '');
                    
                    // Match formats: [number]d[number][+ or -][modifier]? 
                    const match = cleaned.match(/^(\d*)d(\d+)([+-]\d+)?$/);
                    
                    if (!match) {
                        throw new Error("Invalid dice format. Use format like '3d6+2'");
                    }
                    
                    const numDice = match[1] ? parseInt(match[1]) : 1;
                    const diceSize = parseInt(match[2]);
                    const modifier = match[3] ? parseInt(match[3]) : 0;
                    
                    // Some safety limits
                    if (numDice > 20) throw new Error("Too many dice (max 20)");
                    if (diceSize > 100) throw new Error("Dice too large (max d100)");
                    
                    const rolls = [];
                    let total = 0;
                    
                    // Roll each die
                    for (let i = 0; i < numDice; i++) {
                        const roll = Math.floor(Math.random() * diceSize) + 1;
                        rolls.push(roll);
                        total += roll;
                    }
                    
                    total += modifier;
                    
                    return {
                        total,
                        rolls,
                        modifier,
                        numDice,
                        diceSize
                    };
                },
                
                // Clear dice history
                clearDiceHistory() {
                    this.diceHistory = [];
                }
            }));
        });
    </script>
</body>
</html>