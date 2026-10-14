-- Battle App v1 (árvore de torneio) - estrutura da base de dados MySQL
-- Reconstruída a partir das queries do código (a BD original não está no repositório).

CREATE TABLE users (
    login VARCHAR(50)  NOT NULL PRIMARY KEY,
    pass  VARCHAR(255) NOT NULL            -- hash gerado com password_hash() (ver php/login.php)
);

-- 5 lugares de juiz; cada app de juiz reserva um no primeiro arranque
CREATE TABLE judges (
    ID        INT          NOT NULL PRIMARY KEY,
    nome      VARCHAR(50)  NOT NULL,
    connected TINYINT(1)   NOT NULL DEFAULT 0
);

INSERT INTO judges (ID, nome) VALUES
    (1, 'Judge 1'), (2, 'Judge 2'), (3, 'Judge 3'), (4, 'Judge 4'), (5, 'Judge 5');

-- A ordem das colunas importa: selectTournamentsNotPicked() faz SELECT * com bind_result
CREATE TABLE tournaments (
    fldID   INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome    VARCHAR(100) NOT NULL,
    picked  TINYINT(1)   NOT NULL DEFAULT 0,   -- já escolhido pela app do servidor
    ongoing TINYINT(1)   NOT NULL DEFAULT 0,
    ready   TINYINT(1)   NOT NULL DEFAULT 0    -- árvore criada na página Associação
);

CREATE TABLE competitors (
    fldID     INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    genero    VARCHAR(20)  NULL,
    picked    TINYINT(1)   NOT NULL DEFAULT 0,
    IdTorneio INT          NULL,
    fotoURL   VARCHAR(100) NULL,                -- nome do ficheiro em assets/competitors (sem .png)
    FOREIGN KEY (IdTorneio) REFERENCES tournaments(fldID)
);

-- Batalhas do 1.º nível da árvore. Um canto vazio é guardado como '---' com ID 0 (bye).
CREATE TABLE round (
    fldID        INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    blueCorner   VARCHAR(100) NOT NULL,
    blueCornerID INT          NOT NULL DEFAULT 0,
    redCorner    VARCHAR(100) NOT NULL,
    redCornerID  INT          NOT NULL DEFAULT 0,
    tournamentID INT          NOT NULL,
    ordem        INT          NOT NULL,
    FOREIGN KEY (tournamentID) REFERENCES tournaments(fldID)
);
