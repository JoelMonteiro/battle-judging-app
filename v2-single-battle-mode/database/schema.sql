-- Battle App v2 (batalha a batalha) - a única tabela MySQL é a do login da página do servidor.
-- Tudo o resto (votos, pontos, nomes, fotos) vive no Firebase Realtime Database, no nó RoundVoteInfo.

CREATE TABLE users (
    login VARCHAR(50)  NOT NULL PRIMARY KEY,
    pass  VARCHAR(255) NOT NULL   -- hash gerado com password_hash() (ver php/login.php)
);

-- Exemplo (gera o hash com:  php -r "echo password_hash('a_tua_password', PASSWORD_DEFAULT);")
-- INSERT INTO users (login, pass) VALUES ('admin', '$2y$10$...');
