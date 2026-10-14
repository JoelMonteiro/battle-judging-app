# Battle App

Sistema de pontuação em tempo real para **batalhas 1 contra 1 julgadas por um painel de juízes** (3 ou 5). Cada juiz vota no canto azul, no vermelho ou em empate. Os votos aparecem num ecrã público e o sistema conta as rondas e decide o vencedor.

Foi usado em competições reais e passou por duas iterações. Este repositório contém ambas e explica o que correu mal na primeira e porque é que a segunda funcionou.

| | v1: árvore de torneio (2018) | v2: batalha a batalha (2023–2024) |
|---|---|---|
| Juízes | App Android | Página web (uma por juiz) |
| Gestão | App Android "servidor" + BackOffice PHP | Página web protegida por login |
| Display | Página web | Duas páginas web (V1 simples, V2 com fotos) |
| Dados | MySQL + Firebase Realtime Database | Firebase Realtime Database (+ MySQL só para o login) |
| Comportamento em produção | Por vezes bloqueava a meio do torneio | Funcionou sempre |

---

## v2: batalha a batalha (`v2-single-battle-mode/`)

```
judge1/ … judge5/   ← uma página por juiz (abre-se a página do lugar onde o juiz está sentado)
server/             ← gestão: nomes, fotos, torneio, nº de rondas, 3 ou 5 juízes, correção de votos
V1/   V2/           ← displays para o ecrã/projetor (V2 mostra fotos e anima o vencedor)
php/  database/     ← login da página do servidor
```

**Como funciona:** todas as páginas partilham um único nó do Firebase, `RoundVoteInfo`.
- Cada juiz escreve **só os seus campos** (`Vote3`, `Flag3`…) numa escrita atómica (`update`).
- A página do servidor ouve o nó. Quando todos os juízes votaram, soma o ponto ao vencedor da ronda e, quando alguém passa metade das rondas, declara o vencedor da batalha.
- Os displays limitam-se a ouvir e a mostrar.

## v1: árvore de torneio (`v1-tournament-tree-battle-mode/`)

```
client/    ← app Android do juiz (com.battleapp.judge)
server/    ← app Android que gere o torneio (com.battleapp.server)
display/   ← PHP: display público, BackOffice (competidores, torneios), Associação (montar a árvore)
           └── android/  endpoints usados pelas apps
```

Monta-se a árvore no BackOffice (até 16 competidores, 8 batalhas no 1.º nível). A app servidor percorre as batalhas, abre as rondas e vai construindo os níveis seguintes com os vencedores, até à final (melhor de 3; a final é à melhor de 5).

### Post-mortem: porque é que a v1 bloqueava

Em produção, as apps bloqueavam a meio do torneio e era preciso reiniciar a árvore toda. Na altura não descobri a causa. Hoje, ao rever o código, encontrei-a, ou melhor, encontrei várias que se juntavam:

1. **Uma "caixa de correio" partilhada pelos 5 juízes.** Todos escreviam em `VoteInfo/Vote`, `VoteInfo/Nome` e `VoteInfo/ID`, com três escritas separadas. Dois votos quase simultâneos apagavam-se um ao outro, e o servidor podia ler o voto de um juiz com o ID de outro.
2. **O servidor apagava votos acabados de chegar.** Cada alteração em `RoundVoteInfo` fazia reset ao `VoteInfo`, e o próprio servidor atualizava o `RoundVoteInfo` com **18 escritas separadas**, por isso o listener disparava até 18 vezes seguidas.
3. **O resultado de 1 + 2:** o juiz via o seu voto registado (botões desativados), mas o servidor nunca o contava. A ronda ficava à espera para sempre.
4. **Crashes com valores nulos.** Todas as leituras faziam `getValue().toString()`, e bastava faltar um campo para dar `NullPointerException`.
5. **O torneio vivia só na memória do ecrã.** A árvore e os pontos estavam em variáveis da `Activity`. Um crash ou o Android fechar a app em segundo plano significava perder tudo.

Ironicamente, a v2 resolve os pontos 1, 2 e 5 por ser mais simples: cada juiz tem os seus campos, uma escrita por voto, e o estado vive no Firebase.

### O que foi corrigido nesta versão do repositório

- **Um nó por juiz:** `Votes/{id} = { Vote, Name, RoundId }`, gravado numa única escrita. O `RoundId` permite ao servidor ignorar votos de rondas antigas e ao juiz saber se já votou, mesmo depois de reiniciar a app.
- **Sem resets em cadeia.** Cada nó (`RoundInfo`, `RoundVoteInfo`) é atualizado numa única chamada `updateChildren`.
- **O estado do torneio fica em `TournamentState`** (árvore, batalha, pontos, ronda). Se a app do servidor fechar, retoma onde estava.
- Leituras à prova de `null`, listeners removidos no `onDestroy`, uma única `RequestQueue` do Volley e comparação de Strings com `equals`.
- **Login seguro (v1 e v2):** a query antiga tinha injeção de SQL e as passwords estavam em `sha1`. Agora usa prepared statements e `password_hash`/`password_verify`.

> ⚠️ As apps Android foram escritas em 2018 (Android Support Library, Firebase 11, Gradle 3.1). O código corrigido foi verificado com compilação contra stubs das APIs, mas não foi testado num telemóvel. Para o compilar hoje é preciso atualizar o Gradle e migrar para AndroidX.

---

## Como pôr a funcionar

### 1. Firebase
1. Cria um projeto no [Firebase](https://console.firebase.google.com) com o **Realtime Database**.
2. **Web:** copia `js/firebase-config.example.js` para `js/firebase-config.js` (na v2 e em `v1…/display/`) e preenche com os dados do projeto.
3. **Android (v1):** regista as duas apps (`com.battleapp.judge` e `com.battleapp.server`) e coloca cada `google-services.json` na pasta `app/` respetiva.
4. **Regras da base de dados:** não deixes `".write": true` em produção. Restringe a escrita a utilizadores autenticados.

### 2. MySQL + PHP
1. Cria a base de dados com `database/schema.sql` (cada versão tem o seu).
2. Copia `php/init.example.php` para `php/init.php` e, na v1, também `includes/Constants.example.php` para `includes/Constants.php`. Preenche com os dados da tua BD.
3. Cria um utilizador para o login:
   ```bash
   php -r "echo password_hash('a_tua_password', PASSWORD_DEFAULT);"
   ```
   ```sql
   INSERT INTO users (login, pass) VALUES ('admin', '<hash>');
   ```

### 3. Apps Android (v1)
Em `connection/Database.java` (nas duas apps), muda o `ROOT_URL` para o endereço do teu servidor PHP.

Os ficheiros com configurações secretas (`init.php`, `Constants.php`, `firebase-config.js`, `google-services.json`) estão no `.gitignore`.

## Tecnologias
Java (Android) · PHP · MySQL · JavaScript (jQuery) · Firebase Realtime Database · Bootstrap · Volley

## Limitações conhecidas / o que faria diferente hoje
- **Autenticação dos juízes:** as páginas dos juízes não têm login. Hoje usaria Firebase Auth (anónima ou com um código por juiz), ou uma validação na BD MySQL, e regras do Firebase que só deixam cada juiz escrever no seu nó.
- **Os endpoints PHP de gestão** (`display/php/*.php`) não verificam a sessão. Só as páginas é que a verificam.
- **Código repetido:** cinco páginas de juiz quase iguais (`judge1…5`) e blocos `switch` repetidos para cada voto. Hoje seria uma página `judge.html?n=3` e um ciclo.
- A lógica de pontuação da v2 corre no browser da página do servidor. Seria mais robusta numa Cloud Function.

---

## Agradecimentos
- **Hugo Lopes**: pela ideia e por levar a app às competições
- **Miguel Gomes**: pelo design visual original (fontes e imagens), testes e feedback

Desenvolvido por **Joel Monteiro**.
