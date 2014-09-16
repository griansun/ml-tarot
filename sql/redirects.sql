INSERT INTO wp_redirection_groups (id, name, tracking, module_id, status, position) VALUES (3, 'Tarot cards', 1, 1, 'enabled', 2);

INSERT INTO wp_redirection_items (url, action_data, title, last_access, action_type, action_code, match_type, group_id) 
SELECT DISTINCT CONCAT('/tarot/interpretatie/interpretatie.aspx?id=',id), CONCAT('/tarotkaart-betekenissen/tarotkaart-betekenis/', slug), name, NOW(), 'url', '301', 'url', 1 FROM ml_tarotcard;