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
        /* Custom Combat Manager Styles */
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
        
        .combat-manager {
            --primary: var(--color-primary);
            --secondary: var(--color-secondary);
            --tertiary: var(--color-tertiary);
            --accent: var(--color-accent);
        }
        
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
            position: relative;
            z-index: 1;
        }
        
        .dark .combat-manager {
            color: var(--color-dark-text);
            background-color: var(--color-dark-bg);
        }
        
        .header {
            background: var(--color-secondary);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 9, 23, 0.3);
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border: 2px solid var(--color-accent);
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
            border: 2px solid rgba(0, 0, 0, 0.2);
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
            border: 3px solid var(--color-accent);
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
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: var(--color-accent);
            color: var(--color-primary);
            border: none;
            border: 2px solid rgba(0, 0, 0, 0.2);
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
            border: 1px solid rgba(255, 255, 255, 0.2);
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
        
        /* CHANGED: Remove golden border from turn-order container */
        .turn-order {
            display: flex;
            overflow-x: auto;
            gap: 0.75rem; /* Increased gap for better separation */
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            /* Removed border property */
            border-radius: 8px;
            background: rgba(0, 22, 46, 0.1);
        }
        
        .dark .turn-order {
            background: rgba(174, 135, 8, 0.1);
        }
        
        /* CHANGED: Add golden border to individual turn items */
        .turn-item {
            min-width: 120px;
            padding: 0.75rem;
            border-radius: 6px;
            background: var(--color-card);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid var(--color-accent); /* Added golden border */
        }
        
        .dark .turn-item {
            background: var(--color-dark-card);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            border: 2px solid var(--color-accent); /* Golden border for dark mode */
        }
        
        .turn-item.active {
            background: var(--color-accent);
            color: var(--color-primary);
            font-weight: 600;
            border: 2px solid rgba(0, 0, 0, 0.3);
            box-shadow: 0 0 10px rgba(174, 135, 8, 0.5); /* Enhanced active state */
        }
        
        .turn-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .combatants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        /* Updated combatant cards with golden borders */
        .combatant-card {
            background: var(--color-card);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            scroll-margin-top: 100px;
            border: 2px solid var(--color-accent);
            position: relative;
            overflow: hidden;
        }
        
        .combatant-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--color-accent), #d4af37, var(--color-accent));
            z-index: 1;
        }
        
        .dark .combatant-card {
            background: var(--color-dark-card);
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        
        .combatant-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(174, 135, 8, 0.3);
        }
        
        .combatant-card.selected {
            border-left: 4px solid var(--color-accent);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(174, 135, 8, 0.4);
        }
        
        .combatant-card.active-turn {
            border: 3px solid var(--color-accent);
            background: linear-gradient(135deg, var(--color-card) 0%, rgba(174, 135, 8, 0.08) 100%);
            box-shadow: 0 4px 12px rgba(174, 135, 8, 0.25);
        }
        
        .dark .combatant-card.active-turn {
            background: linear-gradient(135deg, var(--color-dark-card) 0%, rgba(174, 135, 8, 0.12) 100%);
        }
        
        .combatant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed rgba(0,0,0,0.1);
        }
        
        .dark .combatant-header {
            border-bottom: 1px dashed rgba(255,255,255,0.1);
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
        
        .detail-header {
            background: var(--color-secondary);
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            border-bottom: 2px solid var(--color-accent);
        }
        
        .detail-content {
            background: var(--color-card);
            border-radius: 0 0 8px 8px;
            padding: 1.5rem;
            border: 2px solid var(--color-accent);
            border-top: none;
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
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dark .stat-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
        
        .save-rolls {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dark .save-rolls {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            border: 1px solid rgba(0, 0, 0, 0.2);
        }
        
        .save-btn:hover {
            background: #003a6e;
            transform: translateY(-2px);
        }
        
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
            border: 1px solid var(--color-accent);
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
            border: 2px solid rgba(0, 0, 0, 0.2);
        }
        
        .scroll-to-info:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
        }
        
        .scroll-to-info.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .conditions-section {
            margin-top: 1rem;
        }
        
        .custom-dice-roller {
            margin-top: 1rem;
            background: rgba(0, 0, 0, 0.1);
            padding: 1rem;
            border-radius: 8px;
            width: 100%;
            border: 2px solid var(--color-accent);
        }
        
        .dark .custom-dice-roller {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .dice-history {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dark .dice-history {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
        
        /* Add Combatant Modal Styles */
        .creature-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .creature-modal.visible {
            opacity: 1;
            pointer-events: all;
        }
        
        .modal-content {
            background: var(--color-card);
            border-radius: 12px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 3px solid var(--color-accent);
        }
        
        .dark .modal-content {
            background: var(--color-dark-card);
        }
        
        .modal-header {
            padding: 1.5rem;
            background: var(--color-secondary);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--color-accent);
        }
        
        .modal-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .search-container {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.5rem;
        }
        
        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,0.1);
            font-size: 1rem;
            border: 1px solid var(--color-accent);
        }
        
        .dark .search-input {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.2);
            color: white;
        }
        
        /* Scrollable creature grid container */
        .creature-grid-container {
            flex: 1;
            overflow-y: auto;
            max-height: 60vh;
            padding-right: 0.5rem;
        }
        
        .creature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .creature-card {
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: rgba(0,0,0,0.03);
            border: 2px solid var(--color-accent);
        }
        
        .dark .creature-card {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.1);
        }
        
        .creature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: var(--color-accent);
        }
        
        .creature-name {
            font-weight: bold;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .creature-stats {
            font-size: 0.85rem;
            color: #666;
        }
        
        .dark .creature-stats {
            color: #aaa;
        }
        
        .no-results {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        .dark .no-results {
            color: #aaa;
        }
        
        /* Scrollbar styling */
        .creature-grid-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .creature-grid-container::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
            border-radius: 4px;
        }
        
        .creature-grid-container::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 4px;
        }
        
        .dark .creature-grid-container::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        
        .dark .creature-grid-container::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
        }
        
        /* Combatant detail section */
        .detail-content {
            padding: 1.5rem;
        }
        
        .conditions-textarea {
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 0.875rem;
            min-height: 100px;
            border: 1px solid var(--color-accent);
        }
        
        .dark .conditions-textarea {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
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
            color: var(--color-accent);
            text-shadow: 0 0 5px rgba(174, 135, 8, 0.5);
        }
        
        .header-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .header-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
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
        
        /* Initiative container */
        .initiative-container {
            display: flex;
            align-items: center;
        }
        
        .initiative-roll-btn {
            background: var(--color-tertiary);
            color: white;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 0.5rem;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }
        
        .initiative-roll-btn:hover {
            background: #003a6e;
            transform: translateY(-1px);
        }
        
        /* Dice animations */
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
        
        .critical-success {
            color: #4ade80;
            text-shadow: 0 0 10px rgba(74, 222, 128, 0.7);
        }
        
        .critical-failure {
            color: #f87171;
            text-shadow: 0 0 10px rgba(248, 113, 113, 0.7);
        }

        /* Tab styles */
        .tabs-container {
            display: flex;
            background: var(--color-secondary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .tab-button {
            padding: 0.75rem 1rem;
            width: 100%;
            text-align: center;
            cursor: pointer;
            background-color: var(--color-secondary);
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease;
            font-weight: 500;
            border-bottom: 3px solid transparent;
        }
        
        .tab-button:hover {
            color: white;
            background-color: rgba(0, 22, 46, 0.8);
        }
        
        .active-tab {
            color: white;
            background-color: var(--color-tertiary);
            border-bottom: 3px solid var(--color-accent);
        }
        
        /* Player form styles */
        .player-form {
            padding: 1rem;
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .player-form input {
            width: 100%;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: white;
            color: var(--color-text);
            border: 1px solid var(--color-accent);
        }
        
        .dark .player-form input {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .player-form label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .player-form .form-group {
            margin-bottom: 1rem;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        
        /* Custom scrollbar for player form */
        .player-form::-webkit-scrollbar {
            width: 10px;
        }
        
        .player-form::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
            border-radius: 4px;
        }
        
        .player-form::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 4px;
        }
        
        .dark .player-form::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        
        .dark .player-form::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
        }
        
        /* Export modal styles */
        .export-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .export-modal.visible {
            opacity: 1;
            pointer-events: all;
        }
        
        .export-content {
            background: var(--color-card);
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 3px solid var(--color-accent);
        }
        
        .dark .export-content {
            background: var(--color-dark-card);
        }
        
        .export-header {
            padding: 1.5rem;
            background: var(--color-secondary);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--color-accent);
        }
        
        .export-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .export-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        
        .export-table th {
            background: var(--color-tertiary);
            color: white;
            padding: 0.75rem;
            text-align: left;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .export-table td {
            padding: 0.75rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dark .export-table td {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .export-table tr:nth-child(even) {
            background: rgba(0,0,0,0.03);
        }
        
        .dark .export-table tr:nth-child(even) {
            background: rgba(255,255,255,0.05);
        }
        
        .export-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        .print-btn {
            background: var(--color-accent);
            color: var(--color-primary);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }
        
        .print-btn:hover {
            background: #c59a0b;
        }
        
        /* CSV Import Modal */
        .csv-import-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .csv-import-modal.visible {
            opacity: 1;
            pointer-events: all;
        }
        
        .csv-modal-content {
            background: var(--color-card);
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 3px solid var(--color-accent);
        }
        
        .dark .csv-modal-content {
            background: var(--color-dark-card);
        }
        
        .csv-modal-header {
            padding: 1.5rem;
            background: var(--color-secondary);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--color-accent);
        }
        
        .csv-modal-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .csv-import-instructions {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(0,0,0,0.05);
            border-radius: 8px;
            font-size: 0.9rem;
            overflow-wrap: break-word;
            border: 1px solid var(--color-accent);
        }
        
        .dark .csv-import-instructions {
            background: rgba(255,255,255,0.05);
        }
        
        .csv-file-input {
            margin-bottom: 1.5rem;
        }
        
        .csv-preview {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid var(--color-accent);
        }
        
        .dark .csv-preview {
            border-color: rgba(255,255,255,0.1);
        }
        
        .csv-preview-table {
            min-width: 100%;
            border-collapse: collapse;
        }
        
        .csv-preview-table th {
            background: rgba(0,0,0,0.05);
            padding: 0.5rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        
        .dark .csv-preview-table th {
            background: rgba(255,255,255,0.05);
        }
        
        .csv-preview-table td {
            padding: 0.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-size: 0.85rem;
            white-space: nowrap;
        }
        
        .dark .csv-preview-table td {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .csv-import-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        .hidden {
            display: none;
        }
        
        /* Tutorial styles - Fixed colors */
        .tutorial-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            display: none;
        }
        
        .tutorial-overlay.visible {
            display: block;
        }
        
        .tutorial-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: var(--color-card);
            color: var(--color-text);
            padding: 2rem;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            z-index: 2001;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            transition: transform 0.3s ease-out;
            border: 3px solid var(--color-accent);
        }
        
        .dark .tutorial-popup {
            background: var(--color-dark-card);
            color: var(--color-dark-text);
        }
        
        .tutorial-popup.visible {
            transform: translate(-50%, -50%) scale(1);
        }
        
        .tutorial-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: var(--color-accent);
        }
        
        .tutorial-content {
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .tutorial-progress {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .dark .tutorial-progress {
            color: #aaa;
        }
        
        .tutorial-buttons {
            display: flex;
            justify-content: space-between;
        }
        
        .tutorial-highlight {
            position: absolute;
            border: 3px solid var(--color-accent);
            border-radius: 8px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
            z-index: 1999;
            pointer-events: none;
            transition: all 0.3s ease;
            display: none; /* Added to fix overlay issue */
        }
        
        .tutorial-skip {
            color: #999;
            cursor: pointer;
            text-decoration: underline;
        }
        
        .dark .tutorial-skip {
            color: #ccc;
        }
        
        /* New styles for better combatant display */
        .combatant-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .combatant-stat {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .combatant-stat-label {
            min-width: 70px;
            font-weight: 500;
        }
        
        .combatant-stat-value {
            font-weight: 600;
            margin-left: 0.25rem;
        }
        
        .initiative-roll-btn {
            margin-left: 0.5rem;
            background: rgba(0,0,0,0.1);
            border-radius: 4px;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .dark .initiative-roll-btn {
            background: rgba(255,255,255,0.1);
            color: var(--color-accent); /* Added for better visibility */
        }
        
        .hp-bar-container {
            height: 8px;
            background: rgba(0,0,0,0.1);
            border-radius: 4px;
            margin-top: 0.25rem;
            overflow: hidden;
        }
        
        .dark .hp-bar-container {
            background: rgba(255,255,255,0.1);
        }
        
        .hp-bar {
            height: 100%;
            background: var(--color-accent);
            transition: width 0.3s ease;
        }
        
        .section-header {
            margin-bottom: 1rem;
        }
        
        .combatant-actions {
            margin-top: 0.5rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .combatant-action-btn {
            padding: 0.25rem 0.5rem;
            background: var(--color-tertiary);
            color: white;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }
        
        /* Added for golden border sections */
        .golden-border-section {
            border: 2px solid var(--color-accent);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        #character-info-section > div {
            border: 2px solid var(--color-accent);
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* New decorative elements */
        .decorative-corner {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid var(--color-accent);
        }
        
        .corner-tl {
            top: 10px;
            left: 10px;
            border-right: none;
            border-bottom: none;
        }
        
        .corner-tr {
            top: 10px;
            right: 10px;
            border-left: none;
            border-bottom: none;
        }
        
        .corner-bl {
            bottom: 10px;
            left: 10px;
            border-right: none;
            border-top: none;
        }
        
        .corner-br {
            bottom: 10px;
            right: 10px;
            border-left: none;
            border-top: none;
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
        
        <!-- Tutorial Overlay -->
        <div class="tutorial-overlay" :class="{ 'visible': tutorialActive }"></div>
        
        <!-- Tutorial Popup -->
        <div class="tutorial-popup" :class="{ 'visible': tutorialActive }">
            <div class="tutorial-title" x-text="currentTutorialStep.title"></div>
            <div class="tutorial-content" x-html="currentTutorialStep.content"></div>
            <div class="tutorial-progress">
                Step <span x-text="currentTutorialIndex + 1"></span> of <span x-text="tutorialSteps.length"></span>
            </div>
            <div class="tutorial-buttons">
                <button 
                    x-show="currentTutorialIndex > 0" 
                    @click="prevTutorialStep"
                    class="btn btn-secondary"
                >
                    <i class="fas fa-arrow-left mr-1"></i> Previous
                </button>
                <button 
                    x-show="currentTutorialIndex === tutorialSteps.length - 1" 
                    @click="endTutorial"
                    class="btn btn-primary"
                >
                    Finish Tutorial
                </button>
                <button 
                    x-show="currentTutorialIndex < tutorialSteps.length - 1" 
                    @click="nextTutorialStep"
                    class="btn btn-primary"
                >
                    Next <i class="fas fa-arrow-right ml-1"></i>
                </button>
            </div>
            <div class="text-right mt-2">
                <span class="tutorial-skip" @click="endTutorial">Skip Tutorial</span>
            </div>
        </div>
        
        <!-- Tutorial Highlight Box -->
        <div class="tutorial-highlight" :style="highlightStyle"></div>
        
        <!-- Add Combatant Modal -->
        <div class="creature-modal" :class="{ 'visible': showAddCreatureModal }">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-xl font-bold">Add Combatant</h3>
                    <button @click="showAddCreatureModal = false" class="text-white text-2xl">&times;</button>
                </div>
                
                <!-- Tabs for Monster/Player -->
                <div class="tabs-container">
                    <button 
                        @click="activeTab = 'monsters'"
                        :class="{
                            'tab-button': true,
                            'active-tab': activeTab === 'monsters'
                        }"
                    >
                        Monsters
                    </button>
                    <button 
                        @click="activeTab = 'players'"
                        :class="{
                            'tab-button': true,
                            'active-tab': activeTab === 'players'
                        }"
                    >
                        Player Characters
                    </button>
                </div>
                
                <div class="modal-body">
                    <!-- Monster Tab -->
                    <div x-show="activeTab === 'monsters'">
                        <div class="search-container">
                            <input 
                                type="text" 
                                x-model="creatureSearch" 
                                placeholder="Search creatures by name..." 
                                class="search-input"
                                @input="filterCreatures"
                            >
                        </div>
                        
                        <div class="creature-grid-container">
                            <div class="creature-grid">
                                <template x-for="creature in filteredCreatures" :key="creature.id">
                                    <div class="creature-card" @click="addCreature(creature)">
                                        <div class="creature-name" x-text="creature.name"></div>
                                        <div class="creature-stats">
                                            <div>Level: <span x-text="creature.level"></span></div>
                                            <div>AC: <span x-text="creature.ac"></span></div>
                                            <div>HP: <span x-text="creature.hp"></span></div>
                                            <div>Perception: <span x-text="creature.perception"></span></div>
                                            <div>Speed: <span x-text="creature.speed"></span></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <div x-show="filteredCreatures.length === 0" class="no-results">
                                <p class="text-lg">No creatures found</p>
                                <p class="mt-2">Try a different search term</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Player Tab -->
                    <div x-show="activeTab === 'players'">
                        <div class="player-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label>Character Name *</label>
                                    <input 
                                        type="text" 
                                        x-model="playerForm.name" 
                                        placeholder="Character name"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label>Class</label>
                                    <input 
                                        type="text" 
                                        x-model="playerForm.class" 
                                        placeholder="Class"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label>Level</label>
                                    <input 
                                        type="number" 
                                        x-model="playerForm.level" 
                                        min="1"
                                        placeholder="Level"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label>Armor Class (AC) *</label>
                                    <input 
                                        type="number" 
                                        x-model="playerForm.ac" 
                                        min="0"
                                        placeholder="AC"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label>Max HP *</label>
                                    <input 
                                        type="number" 
                                        x-model="playerForm.maxHp" 
                                        min="1"
                                        placeholder="Max HP"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label>Perception Bonus</label>
                                    <input 
                                        type="number" 
                                        x-model="playerForm.perception" 
                                        placeholder="Perception"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label>Speed</label>
                                    <input 
                                        type="text" 
                                        x-model="playerForm.speed" 
                                        placeholder="e.g. 25 ft"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label>Saving Throws</label>
                                    <input 
                                        type="text" 
                                        x-model="playerForm.saves" 
                                        placeholder="Fort +X, Ref +Y, Will +Z"
                                    >
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button 
                                    @click="resetPlayerForm" 
                                    class="btn btn-secondary"
                                >
                                    Reset
                                </button>
                                <button 
                                    @click="addPlayerCombatant" 
                                    class="btn btn-primary"
                                    :disabled="!playerForm.name || !playerForm.ac || !playerForm.maxHp"
                                >
                                    Add Player
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Export Modal -->
        <div class="export-modal" :class="{ 'visible': showExportModal }">
            <div class="export-content">
                <div class="export-header">
                    <h3 class="text-xl font-bold">Export Combatants</h3>
                    <button @click="showExportModal = false" class="text-white text-2xl">&times;</button>
                </div>
                
                <div class="export-body">
                    <table class="export-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>AC</th>
                                <th>HP</th>
                                <th>Fortitude</th>
                                <th>Reflex</th>
                                <th>Will</th>
                                <th>Initiative</th>
                                <th>Perception</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="combatant in combatants" :key="combatant.id">
                                <tr>
                                    <td x-text="combatant.name"></td>
                                    <td x-text="combatant.type"></td>
                                    <td x-text="combatant.ac"></td>
                                    <td x-text="`${combatant.currentHp}/${combatant.maxHp}`"></td>
                                    <td x-text="combatant.fortSave || combatant.fortitude || '-'"></td>
                                    <td x-text="combatant.refSave || combatant.reflex || '-'"></td>
                                    <td x-text="combatant.willSave || combatant.will || '-'"></td>
                                    <td x-text="combatant.initiative"></td>
                                    <td x-text="combatant.perception"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <div class="export-actions">
                        <button class="print-btn" @click="printCombatants">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- CSV Import Modal -->
        <div class="csv-import-modal" :class="{ 'visible': showCsvImportModal }">
            <div class="csv-modal-content">
                <div class="csv-modal-header">
                    <h3 class="text-xl font-bold">Import Combatants from CSV</h3>
                    <button @click="showCsvImportModal = false" class="text-white text-2xl">&times;</button>
                </div>
                
                <div class="csv-modal-body">
                    <div class="csv-import-instructions">
                        <p><strong>Instructions:</strong></p>
                        <p>1. Your CSV file should have the following columns: <code>name,type,ac,maxHp,currentHp,initiative,initiativeBonus,speed,perception,saves,fortSave,refSave,willSave,actions,conditions,class,level</code></p>
                        <p>2. The first row should be headers</p>
                        <p>3. Required fields: name, type, ac, maxHp, currentHp</p>
                    </div>
                    
                    <div class="csv-file-input">
                        <input type="file" accept=".csv" @change="handleCsvFileSelect">
                    </div>
                    
                    <div class="csv-preview" x-show="csvPreviewData.length > 0">
                        <h4 class="font-bold mb-2">Preview (first 5 rows)</h4>
                        <table class="csv-preview-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>AC</th>
                                    <th>HP</th>
                                    <th>Initiative</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in csvPreviewData.slice(0, 5)" :key="row.id">
                                    <tr>
                                        <td x-text="row.name"></td>
                                        <td x-text="row.type"></td>
                                        <td x-text="row.ac"></td>
                                        <td x-text="`${row.currentHp}/${row.maxHp}`"></td>
                                        <td x-text="row.initiative"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="csv-import-actions">
                        <button 
                            class="btn btn-secondary" 
                            @click="showCsvImportModal = false"
                        >
                            Cancel
                        </button>
                        <button 
                            class="btn btn-primary" 
                            @click="importCsvData"
                            :disabled="csvPreviewData.length === 0"
                        >
                            Import Combatants
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hidden file input for CSV export -->
        <input type="file" id="csv-import" accept=".csv" class="hidden">
        
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
                        <button class="btn btn-info" @click="startTutorial">
                            <i class="fas fa-graduation-cap mr-1"></i> Tutorial
                        </button>
                        <button class="btn btn-primary" @click="openAddCombatantModal">
                            <i class="fas fa-plus mr-1"></i> Add Combatant
                        </button>
                        <button class="btn btn-secondary" @click="exportCombatants">
                            <i class="fas fa-file-export mr-1"></i> Export
                        </button>
                        <button class="btn btn-secondary" @click="saveCombatState">
                            <i class="fas fa-save mr-1"></i> Save Combat
                        </button>
                        <button class="btn btn-secondary" @click="endCombat">
                            <i class="fas fa-stop mr-1"></i> End Combat
                        </button>
                        <!-- New CSV buttons -->
                        <button class="btn btn-secondary" @click="exportToCSV">
                            <i class="fas fa-file-csv mr-1"></i> Export CSV
                        </button>
                        <button class="btn btn-secondary" @click="showCsvImportModal = true">
                            <i class="fas fa-file-import mr-1"></i> Import CSV
                        </button>
                    </div>
                    
                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme" 
                            class="theme-toggle" 
                            title="Toggle theme">
                    </button>
                </div>
            </div>
            
            <!-- Dice buttons - Reordered to d4, d6, d8, d12, d20, d100 -->
            <div class="dice-header">
                <div class="dice-btn" @click="rollDice('d4')" title="Roll d4">
                    <span>d4</span>
                </div>
                <div class="dice-btn" @click="rollDice('d6')" title="Roll d6">
                    <span>d6</span>
                </div>
                <div class="dice-btn" @click="rollDice('d8')" title="Roll d8">
                    <span>d8</span>
                </div>
                <div class="dice-btn" @click="rollDice('d12')" title="Roll d12">
                    <span>d12</span>
                </div>
                <div class="dice-btn" @click="rollD20" title="Roll d20">
                    <span>d20</span>
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
                            
                            <div class="combatant-stats">
                                <div class="combatant-stat">
                                    <span class="combatant-stat-label">Initiative:</span>
                                    <span class="combatant-stat-value" x-text="combatant.initiative"></span>
                                    <button 
                                        @click.stop="rollInitiative(combatant)"
                                        class="initiative-roll-btn"
                                        title="Roll Initiative"
                                    >
                                        <i class="fas fa-dice"></i>
                                    </button>
                                </div>
                                <div class="combatant-stat">
                                    <span class="combatant-stat-label">AC:</span>
                                    <span class="combatant-stat-value" x-text="combatant.ac"></span>
                                </div>
                                <template x-if="combatant.class">
                                    <div class="combatant-stat">
                                        <span class="combatant-stat-label">Class:</span>
                                        <span class="combatant-stat-value" x-text="combatant.class"></span>
                                    </div>
                                </template>
                                <template x-if="combatant.level">
                                    <div class="combatant-stat">
                                        <span class="combatant-stat-label">Level:</span>
                                        <span class="combatant-stat-value" x-text="combatant.level"></span>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="mt-1">
                                <div class="flex items-center justify-between mb-1">
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
                                
                                <div class="hp-bar-container">
                                    <div class="hp-bar" :style="'width: ' + (combatant.currentHp / combatant.maxHp * 100) + '%'"></div>
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
                                        class="conditions-input w-full p-1 border rounded text-sm" 
                                        placeholder="Add conditions (comma separated)"
                                    >
                                </div>
                                
                                <!-- Quick Actions -->
                                <div class="combatant-actions">
                                    <button class="combatant-action-btn" @click.stop="rollAttack(combatant)">
                                        Attack
                                    </button>
                                    <button class="combatant-action-btn" @click.stop="rollDamage(combatant)">
                                        Damage
                                    </button>
                                    <button class="combatant-action-btn" @click.stop="addCondition(combatant, 'Flat-footed')">
                                        Flat-footed
                                    </button>
                                    <button class="combatant-action-btn" @click.stop="addCondition(combatant, 'Stunned')">
                                        Stunned
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Combatant Detail -->
            <div class="lg:col-span-1" id="character-info-section">
                <div class="golden-border-section">
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
                                        <div class="stat-value" x-text="selectedCombatant.speed || '30 ft'"></div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Perception</div>
                                        <div class="stat-value" x-text="selectedCombatant.perception || '+5'"></div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Saves</div>
                                        <div class="stat-value" x-text="selectedCombatant.saves || '+6/+4/+8'"></div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Actions</div>
                                        <div class="stat-value" x-text="selectedCombatant.actions || '3'"></div>
                                    </div>
                                </div>
                                
                                <!-- Stat Block -->
                                <template x-if="selectedCombatant.type === 'monster'">
                                    <div>
                                        <!-- Saving Throw Roller -->
                                        <div class="save-rolls">
                                            <h4 class="font-bold text-lg mb-2">
                                                <i class="fas fa-shield-alt"></i> Saving Throws
                                            </h4>
                                            <div class="save-buttons">
                                                <button class="save-btn" @click="rollSave('Fortitude', selectedCombatant.fortSave || 8)">
                                                    Fortitude <span x-text="selectedCombatant.fortSave || 8"></span>
                                                </button>
                                                <button class="save-btn" @click="rollSave('Reflex', selectedCombatant.refSave || 5)">
                                                    Reflex <span x-text="selectedCombatant.refSave || 5"></span>
                                                </button>
                                                <button class="save-btn" @click="rollSave('Will', selectedCombatant.willSave || 7)">
                                                    Will <span x-text="selectedCombatant.willSave || 7"></span>
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
                                                    <strong>Class:</strong> <span x-text="selectedCombatant.class || 'Fighter'"></span>
                                                </div>
                                                <div class="mb-2 text-sm">
                                                    <strong>Level:</strong> <span x-text="selectedCombatant.level || '5'"></span>
                                                </div>
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
    </div>

    <!-- Pass PHP data to JavaScript -->
    <script>
        window.creaturesFromDatabase = [
            { id: 1, name: "Orc Warrior", level: 2, ac: 18, hp: 30, perception: 4, speed: "25 ft", fortitude: 8, reflex: 4, will: 6 },
            { id: 2, name: "Goblin Archer", level: 1, ac: 16, hp: 20, perception: 5, speed: "25 ft", fortitude: 6, reflex: 8, will: 4 },
            { id: 3, name: "Skeleton Guard", level: 1, ac: 15, hp: 22, perception: 3, speed: "25 ft", fortitude: 7, reflex: 5, will: 5 },
            { id: 4, name: "Wolf", level: 1, ac: 14, hp: 18, perception: 6, speed: "40 ft", fortitude: 5, reflex: 7, will: 4 },
            { id: 5, name: "Zombie", level: 1, ac: 13, hp: 25, perception: 2, speed: "20 ft", fortitude: 8, reflex: 3, will: 5 },
            { id: 6, name: "Giant Spider", level: 2, ac: 17, hp: 28, perception: 7, speed: "30 ft, climb 30 ft", fortitude: 7, reflex: 8, will: 5 }
        ];
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('combatManager', () => ({
                combatants: [
                    { id: 1, name: "Caden Kilback", type: "monster", ac: 18, maxHp: 42, currentHp: 42, initiative: 22, active: true, class: "Orc Warrior", level: 3 },
                    { id: 2, name: "Goblin Archer", type: "monster", ac: 16, maxHp: 27, currentHp: 27, initiative: 27, class: "Goblin Archer", level: 1 },
                    { id: 3, name: "Ann", type: "player", ac: 17, maxHp: 27, currentHp: 27, initiative: 27, class: "Rogue", level: 4 },
                    { id: 4, name: "Dr. Kurt Thiel", type: "monster", ac: 19, maxHp: 20, currentHp: 20, initiative: 20, class: "Necromancer", level: 5 },
                    { id: 5, name: "Jack", type: "player", ac: 15, maxHp: 15, currentHp: 15, initiative: 15, class: "Wizard", level: 3 },
                    { id: 6, name: "Dave", type: "player", ac: 16, maxHp: 28, currentHp: 28, initiative: 8, class: "Cleric", level: 4 },
                    { id: 7, name: "Charity Torphy", type: "player", ac: 14, maxHp: 22, currentHp: 22, initiative: 12, class: "Bard", level: 3 }
                ],
                selectedCombatant: null,
                theme: 'dark',
                showDiceResult: false,
                diceResult: 0,
                diceLabel: "",
                diceCritical: null,
                customDiceExpression: "",
                diceHistory: [],
                tutorialActive: false,
                currentTutorialIndex: 0,
                highlightStyle: '',
                tutorialSteps: [
                    {
                        title: "Welcome to Combat Manager",
                        content: "This tutorial will guide you through the main features of the Pathfinder 2e Combat Manager.<br><br>You'll learn how to add combatants, manage turns, track HP, and use dice rollers.",
                        element: null
                    },
                    {
                        title: "Adding Combatants",
                        content: "Click the <strong>Add Combatant</strong> button to add monsters or player characters to your combat.<br><br>You can add creatures from the database or create custom player characters.",
                        element: ".header-buttons .btn:nth-child(2)"
                    },
                    {
                        title: "Turn Order",
                        content: "The turn order bar shows all combatants in initiative order.<br><br>Click on a combatant's card to set their turn as active.",
                        element: ".turn-order"
                    },
                    {
                        title: "Combatant Cards",
                        content: "Each combatant has a card showing their vital stats.<br><br>Click on a card to select it and view detailed information.",
                        element: ".combatant-card"
                    },
                    {
                        title: "Managing Health",
                        content: "Use the <strong>HP controls</strong> to adjust a combatant's health.<br><br>You can also edit the HP number directly.",
                        element: ".hp-controls"
                    },
                    {
                        title: "Dice Rollers",
                        content: "Use the dice buttons to quickly roll dice.<br><br>The custom dice roller lets you enter complex expressions like 2d8+5.",
                        element: ".dice-header"
                    },
                    {
                        title: "Combatant Details",
                        content: "The details panel shows all information for the selected combatant.<br><br>Here you can edit conditions, view stats, and roll saving throws.",
                        element: "#character-info-section"
                    },
                    {
                        title: "Tutorial Complete",
                        content: "You've completed the tutorial!<br><br>Remember you can always restart this tutorial using the <strong>Tutorial</strong> button.",
                        element: null
                    }
                ],
                
                init() {
                    // Initialize theme
                    const savedTheme = localStorage.getItem('theme');
                    this.theme = savedTheme || 'dark';
                    document.documentElement.className = this.theme;
                    
                    // Initialize creature list from database
                    this.allCreatures = window.creaturesFromDatabase || [];
                    this.filteredCreatures = [...this.allCreatures];
                    
                    // Load combat state from localStorage
                    this.loadCombatState();
                    
                    // Sort by initiative (highest first)
                    this.combatants.sort((a, b) => b.initiative - a.initiative);
                    
                    // Select first combatant by default if none selected
                    if (this.combatants.length > 0 && !this.selectedCombatant) {
                        this.selectedCombatant = this.combatants[0];
                    }
                    
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
                
                // Save combat state to localStorage
                saveCombatState() {
                    try {
                        // Create a simplified state for saving
                        const state = {
                            combatants: this.combatants.map(c => ({
                                id: c.id,
                                name: c.name,
                                type: c.type,
                                ac: c.ac,
                                maxHp: c.maxHp,
                                currentHp: c.currentHp,
                                initiative: c.initiative,
                                initiativeBonus: c.initiativeBonus,
                                speed: c.speed,
                                perception: c.perception,
                                saves: c.saves,
                                fortSave: c.fortSave,
                                refSave: c.refSave,
                                willSave: c.willSave,
                                actions: c.actions,
                                active: c.active,
                                conditions: c.conditions,
                                class: c.class,
                                level: c.level
                            })),
                            selectedCombatantId: this.selectedCombatant ? this.selectedCombatant.id : null
                        };
                        
                        localStorage.setItem('combatState', JSON.stringify(state));
                        
                        // Show success message
                        alert('Combat state saved successfully!');
                    } catch (error) {
                        console.error('Error saving combat state:', error);
                        alert('Error saving combat state. See console for details.');
                    }
                },
                
                // Load combat state from localStorage
                loadCombatState() {
                    try {
                        const savedState = localStorage.getItem('combatState');
                        if (savedState) {
                            const state = JSON.parse(savedState);
                            
                            // Restore combatants
                            this.combatants = state.combatants || [];
                            
                            // Restore selected combatant
                            if (state.selectedCombatantId) {
                                this.selectedCombatant = this.combatants.find(
                                    c => c.id === state.selectedCombatantId
                                );
                            }
                            
                            // Re-sort combatants
                            this.combatants.sort((a, b) => b.initiative - a.initiative);
                            
                            // Show status message
                            console.log('Combat state loaded successfully');
                        }
                    } catch (e) {
                        console.error('Error loading combat state:', e);
                    }
                },
                
                // End combat
                endCombat() {
                    if (confirm('Are you sure you want to end this combat?')) {
                        // Clear combat state
                        this.combatants = [];
                        this.selectedCombatant = null;
                        localStorage.removeItem('combatState');
                        alert('Combat ended successfully!');
                    }
                },
                
                // Filter creatures based on search term
                filterCreatures() {
                    if (!this.creatureSearch) {
                        this.filteredCreatures = [...this.allCreatures];
                        return;
                    }
                    
                    const searchTerm = this.creatureSearch.toLowerCase();
                    this.filteredCreatures = this.allCreatures.filter(creature => 
                        creature.name.toLowerCase().includes(searchTerm)
                    );
                },
                
                // Add a creature to combat
                addCreature(creature) {
                    const newCombatant = {
                        id: Date.now(),
                        name: creature.name,
                        type: 'monster',
                        ac: creature.ac,
                        maxHp: creature.hp,
                        currentHp: creature.hp,
                        initiative: 0,
                        initiativeBonus: creature.perception,
                        speed: creature.speed,
                        perception: `+${creature.perception}`,
                        saves: `Fort +${creature.fortitude}, Ref +${creature.reflex}, Will +${creature.will}`,
                        fortSave: creature.fortitude,
                        refSave: creature.reflex,
                        willSave: creature.will,
                        actions: '3',
                        active: false,
                        conditions: ''
                    };

                    this.combatants.push(newCombatant);
                    this.showAddCreatureModal = false;
                    
                    // Sort combatants
                    this.combatants.sort((a, b) => b.initiative - a.initiative);
                    
                    // Save state
                    this.saveCombatState();
                    
                    // Select the new combatant
                    this.selectCombatant(newCombatant);
                },
                
                // Open the add combatant modal with proper initialization
                openAddCombatantModal() {
                    this.showAddCreatureModal = true;
                    this.activeTab = 'monsters';
                    this.creatureSearch = '';
                    this.filterCreatures();
                    this.resetPlayerForm();
                },
                
                // Reset player form to default values
                resetPlayerForm() {
                    this.playerForm = {
                        name: '',
                        class: '',
                        level: 1,
                        ac: 0,
                        maxHp: 0,
                        perception: 0,
                        speed: '25 ft',
                        saves: 'Fort +0, Ref +0, Will +0'
                    };
                },
                
                // Add a player combatant from the form
                addPlayerCombatant() {
                    if (!this.playerForm.name || !this.playerForm.ac || !this.playerForm.maxHp) {
                        alert('Please fill in all required fields (Name, AC, and Max HP)');
                        return;
                    }

                    const newPlayer = {
                        id: Date.now(),
                        name: this.playerForm.name,
                        type: 'player',
                        ac: parseInt(this.playerForm.ac),
                        maxHp: parseInt(this.playerForm.maxHp),
                        currentHp: parseInt(this.playerForm.maxHp),
                        initiative: 0,
                        initiativeBonus: parseInt(this.playerForm.perception) || 0,
                        speed: this.playerForm.speed || '25 ft',
                        class: this.playerForm.class || '',
                        level: parseInt(this.playerForm.level) || 1,
                        perception: this.playerForm.perception ? `+${this.playerForm.perception}` : '+0',
                        saves: this.playerForm.saves || 'Fort +0, Ref +0, Will +0',
                        actions: '3',
                        active: false,
                        conditions: ''
                    };

                    this.combatants.push(newPlayer);
                    this.combatants.sort((a, b) => b.initiative - a.initiative);
                    this.saveCombatState();
                    this.selectCombatant(newPlayer);
                    this.showAddCreatureModal = false;
                    this.resetPlayerForm();
                },
                
                // Export combatants for printing
                exportCombatants() {
                    if (this.combatants.length === 0) {
                        alert('No combatants to export!');
                        return;
                    }
                    this.showExportModal = true;
                },
                
                printCombatants() {
                    // Create a temporary iframe for printing
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    document.body.appendChild(iframe);
                    
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    
                    // Write print-specific content to iframe
                    iframeDoc.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Combat Export</title>
                            <style>
                                @page { size: landscape; margin: 5mm; }
                                body { font-family: Arial; font-size: 9pt; }
                                table { width: 100%; border-collapse: collapse; }
                                th { background: #00162E; color: white; padding: 2mm; text-align: left; }
                                td { padding: 2mm; border: 1px solid #ddd; }
                                tr:nth-child(even) { background: #f8f8f8; }
                            </style>
                        </head>
                        <body>
                            <h1 style="text-align:center">Pathfinder 2e Combat Export</h1>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>AC</th>
                                        <th>HP</th>
                                        <th>Fortitude</th>
                                        <th>Reflex</th>
                                        <th>Will</th>
                                        <th>Initiative</th>
                                        <th>Perception</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${this.combatants.map(c => `
                                        <tr>
                                            <td>${c.name}</td>
                                            <td>${c.type}</td>
                                            <td>${c.ac}</td>
                                            <td>${c.currentHp}/${c.maxHp}</td>
                                            <td>${c.fortSave || c.fortitude || '-'}</td>
                                            <td>${c.refSave || c.reflex || '-'}</td>
                                            <td>${c.willSave || c.will || '-'}</td>
                                            <td>${c.initiative}</td>
                                            <td>${c.perception}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                            <div style="text-align:center;margin-top:5mm">
                                Generated by Pathfinder 2e Combat Manager
                            </div>
                        </body>
                        </html>
                    `);
                    
                    iframeDoc.close();
                    
                    // Print the iframe content
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    
                    // Clean up
                    setTimeout(() => document.body.removeChild(iframe), 1000);
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
                
                // Roll attack
                rollAttack(combatant) {
                    const roll = Math.floor(Math.random() * 20) + 1;
                    const bonus = combatant.attackBonus || 0;
                    const total = roll + bonus;
                    this.showDiceResult = true;
                    this.diceResult = total;
                    this.diceLabel = `${combatant.name} Attack`;
                    this.diceCritical = null;
                    if (roll === 20) this.diceCritical = 'success';
                    if (roll === 1) this.diceCritical = 'failure';
                    setTimeout(() => {
                        this.showDiceResult = false;
                    }, 2000);
                },
                
                // Roll damage
                rollDamage(combatant) {
                    const damageRoll = Math.floor(Math.random() * 8) + 1 + Math.floor(Math.random() * 8) + 1;
                    const bonus = combatant.damageBonus || 0;
                    const total = damageRoll + bonus;
                    this.showDiceResult = true;
                    this.diceResult = total;
                    this.diceLabel = `${combatant.name} Damage`;
                    this.diceCritical = null;
                    setTimeout(() => {
                        this.showDiceResult = false;
                    }, 2000);
                },
                
                // Add condition to combatant
                addCondition(combatant, condition) {
                    if (!combatant.conditions) {
                        combatant.conditions = condition;
                    } else if (!combatant.conditions.includes(condition)) {
                        combatant.conditions += ', ' + condition;
                    }
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
                },
                
                // Export combatants to CSV
                exportToCSV() {
                    if (this.combatants.length === 0) {
                        alert('No combatants to export!');
                        return;
                    }
                    
                    // Define CSV headers
                    const headers = [
                        'name', 'type', 'ac', 'maxHp', 'currentHp', 'initiative', 
                        'initiativeBonus', 'speed', 'perception', 'saves', 'fortSave', 
                        'refSave', 'willSave', 'actions', 'conditions', 'class', 'level'
                    ];
                    
                    // Create CSV content
                    let csvContent = headers.join(',') + '\n';
                    
                    this.combatants.forEach(combatant => {
                        const row = headers.map(header => {
                            let value = combatant[header] || '';
                            
                            // Handle special cases
                            if (header === 'active') {
                                value = combatant.active ? '1' : '0';
                            }
                            
                            // Escape quotes and wrap in quotes if the field contains a comma
                            if (typeof value === 'string' && (value.includes(',') || value.includes('"'))) {
                                value = `"${value.replace(/"/g, '""')}"`;
                            }
                            
                            return value;
                        }).join(',');
                        
                        csvContent += row + '\n';
                    });
                    
                    // Create a Blob and download
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'pathfinder-combat-encounter.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                
                // Handle CSV file selection
                handleCsvFileSelect(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const csvData = e.target.result;
                        this.parseCsvData(csvData);
                    };
                    reader.readAsText(file);
                },
                
                // Parse CSV data
                parseCsvData(csvData) {
                    this.csvPreviewData = [];
                    
                    // Split CSV into lines
                    const lines = csvData.split('\n').filter(line => line.trim() !== '');
                    
                    // Extract headers
                    const headers = lines[0].split(',').map(header => header.trim());
                    
                    // Process each row
                    for (let i = 1; i < lines.length; i++) {
                        const line = lines[i];
                        const values = line.split(',');
                        
                        // Skip if not enough values
                        if (values.length < headers.length) continue;
                        
                        const combatant = {};
                        
                        headers.forEach((header, index) => {
                            let value = values[index].trim();
                            
                            // Remove quotes if present
                            if (value.startsWith('"') && value.endsWith('"')) {
                                value = value.substring(1, value.length - 1);
                            }
                            
                            // Convert numeric fields
                            if ([
                                'ac', 'maxHp', 'currentHp', 'initiative', 
                                'initiativeBonus', 'fortSave', 'refSave', 
                                'willSave', 'level'
                            ].includes(header)) {
                                value = value ? parseFloat(value) : 0;
                            }
                            
                            // Convert boolean fields
                            if (header === 'active') {
                                value = value === '1';
                            }
                            
                            combatant[header] = value;
                        });
                        
                        // Generate a unique ID
                        combatant.id = Date.now() + i;
                        
                        // Set default values if missing
                        if (!combatant.actions) combatant.actions = '3';
                        if (!combatant.conditions) combatant.conditions = '';
                        if (!combatant.active) combatant.active = false;
                        
                        this.csvPreviewData.push(combatant);
                    }
                },
                
                // Import CSV data into combatants
                importCsvData() {
                    if (this.csvPreviewData.length === 0) {
                        alert('No valid combatants to import!');
                        return;
                    }
                    
                    // Clear current combatants
                    this.combatants = [];
                    
                    // Add imported combatants
                    this.csvPreviewData.forEach(combatant => {
                        this.combatants.push(combatant);
                    });
                    
                    // Sort by initiative
                    this.combatants.sort((a, b) => b.initiative - a.initiative);
                    
                    // Select first combatant
                    if (this.combatants.length > 0) {
                        this.selectedCombatant = this.combatants[0];
                    }
                    
                    // Save state
                    this.saveCombatState();
                    
                    // Close modal
                    this.showCsvImportModal = false;
                    this.csvPreviewData = [];
                    
                    alert(`Successfully imported ${this.combatants.length} combatants!`);
                },
                
                // Tutorial methods
                startTutorial() {
                    this.tutorialActive = true;
                    this.currentTutorialIndex = 0;
                    this.positionTutorialHighlight();
                },
                
                nextTutorialStep() {
                    if (this.currentTutorialIndex < this.tutorialSteps.length - 1) {
                        this.currentTutorialIndex++;
                        this.positionTutorialHighlight();
                    }
                },
                
                prevTutorialStep() {
                    if (this.currentTutorialIndex > 0) {
                        this.currentTutorialIndex--;
                        this.positionTutorialHighlight();
                    }
                },
                
                endTutorial() {
                    this.tutorialActive = false;
                    this.highlightStyle = '';
                },
                
                positionTutorialHighlight() {
                    const step = this.tutorialSteps[this.currentTutorialIndex];
                    if (!step.element) {
                        this.highlightStyle = '';
                        return;
                    }
                    this.$nextTick(() => {
                        const element = document.querySelector(step.element);
                        if (element) {
                            const rect = element.getBoundingClientRect();
                            this.highlightStyle = `
                                top: ${rect.top - 10}px;
                                left: ${rect.left - 10}px;
                                width: ${rect.width + 20}px;
                                height: ${rect.height + 20}px;
                            `;
                            element.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    });
                },
                
                get currentTutorialStep() {
                    return this.tutorialSteps[this.currentTutorialIndex];
                }
            }));
        });
    </script>
</body>
</html>