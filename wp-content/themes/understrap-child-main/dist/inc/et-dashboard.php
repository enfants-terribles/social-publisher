<?php
function get_wp_system_status() {
    global $wpdb;
    $wp_version = get_bloginfo('version');
    $php_version = phpversion();

    return '<p style="font-size: 18px;"><strong>Systemstatus</strong></p>
    <ul>
        <li>WordPress-Version: ' . esc_html($wp_version) . '</li>
        <li>PHP-Version: ' . esc_html($php_version) . '</li>
    </ul>';
}

function get_last_backup_date() {
    $backups = get_option('updraft_backup_history');

    if (!$backups || !is_array($backups) || empty($backups)) {
        return '<p><strong>Letztes Backup:</strong> Kein Backup gefunden</p>';
    }

    // Stelle sicher, dass die Keys (Timestamps) numerisch sind und sortiere sie absteigend
    $backup_keys = array_keys($backups);
    rsort($backup_keys); // Neueste zuerst

    $latest_backup = reset($backup_keys); // Nimm das erste Element nach Sortierung (neustes Backup)

    // Korrigiere die Zeitverschiebung basierend auf der WordPress-Zeitzone
    $gmt_offset = get_option('gmt_offset') * 3600;
    $adjusted_time = $latest_backup + $gmt_offset;

    return '<p><strong>Letztes Backup:</strong> ' . date('d.m.Y H:i', $adjusted_time) . '</p>';
}

function get_support_links() {
    return '<p style="font-size: 18px;"><strong>Hilfe & Support:</strong></p>
    <ul>
        <li><a href="mailto:it@enfants.de">Support E-Mail</a></li>
    </ul>';
}

function chatgpt_dashboard_section() {
    $api_key = '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'; // <---- Ersetze das mit deinem API Key
    $user_input = isset($_POST['chatgpt_question']) ? sanitize_text_field($_POST['chatgpt_question']) : '';
    $response_text = '';

    if (!empty($user_input)) {
        $response_text = chatgpt_get_response($api_key, $user_input);
    }

    echo '<div style="margin-top: 20px; padding: 10px; border-top: 1px solid #ddd;">
        <h3>💬 Frage ChatGPT</h3>
        <form method="post" id="chatgpt-form" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="chatgpt_question" id="chatgpt-input" placeholder="Los gehts ..." style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            <input type="submit" value="Senden" id="chatgpt-submit" style="padding: 15px 15px; background-color: #0073aa; color: white; border: none; border-radius: 5px; cursor: pointer; transition: 0.3s;">
        </form>';

    if (!empty($response_text)) {
        echo '<p><strong>Antwort:</strong></p>';
        echo '<p>' . esc_html($response_text) . '</p>';
    }

    echo '</div>';

    // JavaScript für Button-Styling
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("chatgpt-form");
            const submitButton = document.getElementById("chatgpt-submit");

            form.addEventListener("submit", function() {
                submitButton.value = "Lädt...";
                submitButton.style.backgroundColor = "#aaa"; // Button grau machen
                submitButton.style.cursor = "not-allowed"; // Kein Zeiger mehr
                submitButton.disabled = true; // Button deaktivieren
            });
        });
    </script>';
}

function chatgpt_get_response($api_key, $user_input) {
    $api_url = 'https://api.openai.com/v1/chat/completions';
    $body = json_encode([
        'model' => 'gpt-4',
        'messages' => [['role' => 'user', 'content' => $user_input]],
        'temperature' => 0.7
    ]);

    $response = wp_remote_post($api_url, [
        'body'    => $body,
        'timeout' => 30, // Timeout auf 30 Sekunden erhöhen
        'headers' => [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $api_key
        ]
    ]);

    if (is_wp_error($response)) {
        return 'Fehler: ' . $response->get_error_message();
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['error'])) {
        return 'API Fehler: ' . esc_html($body['error']['message']);
    }

    return isset($body['choices'][0]['message']['content']) ? $body['choices'][0]['message']['content'] : 'Keine Antwort erhalten.';
}

function get_lighthouse_scores($url) {
    $api_key = 'DEIN_GOOGLE_API_KEY'; // <-- Ersetze mit deinem API Key
    $api_url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=" . urlencode($url) . "&strategy=mobile&key=" . $api_key;

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return 'Fehler beim Abrufen der Lighthouse-Daten.';
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['lighthouseResult']['categories'])) {
        $performance = round($body['lighthouseResult']['categories']['performance']['score'] * 100);
        $accessibility = round($body['lighthouseResult']['categories']['accessibility']['score'] * 100);
        $best_practices = round($body['lighthouseResult']['categories']['best-practices']['score'] * 100);
        $seo = round($body['lighthouseResult']['categories']['seo']['score'] * 100);

        return "
        <p><strong>🔍 Lighthouse Scores:</strong></p>
        <ul>
            <li>⚡ Performance: <strong>{$performance}</strong></li>
            <li>🔑 Accessibility: <strong>{$accessibility}</strong></li>
            <li>📋 Best Practices: <strong>{$best_practices}</strong></li>
            <li>🔍 SEO: <strong>{$seo}</strong></li>
        </ul>";
    }

    return 'Keine Lighthouse-Daten verfügbar.';
}

function get_matomo_statistics() {
    $matomo_api_url = 'https://www.enfants.de/analytics/matomo/';
    $matomo_token = '67bce13fd79ada21ba83249a0945bc09';
    $idSite = 1; // Matomo Seiten-ID für diese WP-Seite
    // API-URLs für Wochenstatistik & Echtzeit-Besucher
    $stats_url = $matomo_api_url . "?module=API&method=VisitsSummary.get&format=json&idSite={$idSite}&period=week&date=today&token_auth={$matomo_token}";
    $live_url = $matomo_api_url . "?module=API&method=Live.getCounters&format=json&idSite={$idSite}&lastMinutes=5&token_auth={$matomo_token}";

    // Besucher & Seitenaufrufe (letzte 7 Tage)
    $response_stats = wp_remote_get($stats_url);
    if (is_wp_error($response_stats)) {
        return '<p style="color: red;">Fehler: Matomo-Wochenstatistiken konnten nicht geladen werden.</p>';
    }

    $data_stats = json_decode(wp_remote_retrieve_body($response_stats), true);
    if (!$data_stats) {
        return '<p style="color: red;">Keine Matomo-Wochenstatistiken verfügbar.</p>';
    }

    // Echtzeit-Besucher (letzte 5 Minuten)
    $response_live = wp_remote_get($live_url);
    if (is_wp_error($response_live)) {
        return '<p style="color: red;">Fehler: Echtzeit-Daten konnten nicht geladen werden.</p>';
    }

    $data_live = json_decode(wp_remote_retrieve_body($response_live), true);
    $realtime_visitors = isset($data_live[0]['visitors']) ? intval($data_live[0]['visitors']) : 0;

    // Matomo-Wochenstatistiken abrufen
    $visits = isset($data_stats['nb_visits']) ? intval($data_stats['nb_visits']) : 0;
    $actions = isset($data_stats['nb_actions']) ? intval($data_stats['nb_actions']) : 0;
    $bounce_rate = isset($data_stats['bounce_rate']) ? $data_stats['bounce_rate'] : 'N/A';

    return "
    <p style='font-size: 18px;'><strong>Matomo-Statistiken:</strong></p>
    <ul>
        <li>Besucher (7 Tage): {$visits}</li>
        <li>Seitenaufrufe (Aktionen): {$actions}</li>
        <li>Absprungrate: {$bounce_rate}</li>
        <li>Aktuell online: {$realtime_visitors} Besucher</li>
    </ul>";
}

function get_recent_posts() {
    $recent_posts = wp_get_recent_posts([
        'numberposts' => 5,
        'post_status' => ['publish', 'future'],
        'post_type'   => 'post'
    ]);

    if (empty($recent_posts)) {
        return '<p>📝 Keine aktuellen oder geplanten Beiträge.</p>';
    }

    $output = '<p style="font-size: 18px;"><strong>Letzte & geplante Beiträge:</strong></p><ul>';
    foreach ($recent_posts as $post) {
        $date = date("d.m.Y H:i", strtotime($post['post_date']));
        $status = ($post['post_status'] == 'future') ? 'Geplant' : 'Veröffentlicht';
        $output .= "<li><a href='" . get_permalink($post['ID']) . "'>{$post['post_title']}</a> ({$status} - {$date})</li>";
    }
    $output .= '</ul>';

    return $output;
}

function custom_dashboard_widget() {
    $logo_url = 'https://www.enfants.de/wp-content/uploads/2025/02/logo_small.png'; // Pfad zum Logo anpassen
    $contact_name = 'Steffen Müller'; // Ansprechpartner
    $contact_email = 'it@enfants.de'; // Support-E-Mail

    echo '<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">';
    echo '<a href="https://www.enfants.de" target="_blank"><img src="' . esc_url($logo_url) . '" alt="Enfants Logo" style="max-height: 50px;"></a>';
    // echo '<h2 style="margin: 0;">Enfants Dashboard</h2>';
    echo '</div>';

    echo '<p><strong>Kontakt:</strong> ' . esc_html($contact_name) . '<br>';
    echo '<a href="mailto:' . esc_attr($contact_email) . '">' . esc_html($contact_email) . '</a></p>';

    echo '<hr>';

    // Systemstatus (Fehlertolerant)
    echo get_wp_system_status();

    // Letztes Backup (Fehlertolerant)
    echo get_last_backup_date();

    // Matomo-Statistiken
    echo get_matomo_statistics();

    // Support-Links
    echo get_support_links();

    echo get_recent_posts();

    // ChatGPT-Integration
    chatgpt_dashboard_section();
}

function add_custom_dashboard_widget() {
    wp_add_dashboard_widget(
        'custom_dashboard_widget',
        'Enfants Dashboard',
        'custom_dashboard_widget'
    );
}

add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');

function remove_all_dashboard_widgets_except_custom() {
    global $wp_meta_boxes;

    // Standard-Widgets entfernen
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Aktivitäts-Widget
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // "Auf einen Blick"
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Schnellentwurf
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress News
    remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // Zweites WordPress News Widget
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal'); // Site-Health
    remove_meta_box('dashboard_welcome', 'dashboard', 'normal'); // Willkommen-Widget
}

// Diese Funktion muss NACH unserem Widget ausgeführt werden!
add_action('wp_dashboard_setup', 'remove_all_dashboard_widgets_except_custom', 999);

?>