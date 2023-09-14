---
comments: vero
---

# Installazione
Questa guida vi aiuterà a costruire il progetto sulla vostra macchina.

## Tabella dei contenuti
- [Introduzione](#introduction)
- [Limitazioni](#limitations)
- [Step-by-step guide](#step-by-step-guide)
  - [1. Scarica il repository](#1-download-the-repository)
  - [2. Installa Rust](#2-install-rust)
  - [3. Install NPM](#3-install-npm)
  - [4. Installa Next.js](#4-install-nextjs)
  - [5. Installa Tauri](#5-install-tauri)
  - [6. Installa dipendenze progetto](#6-install-project-dependencies)
  - [7. Genera il progetto](#7-build-the-project)

- [Conclusion](#conclusion) https://github.com/Raspirus/Raspirus/releases
## Introduzione
For people that just want a working app, they can just head over to the [Release page](https://github.com/Raspirus/Raspirus/releases/latest) and download the executable for the correct platform. But if you are on a different Linux distribution, unsupported OS, or just want to compile the project on your own, this step-by-step guide will guide you.

## Limitazioni
- Glibc può causare problemi su Linux: https://tauri.app/v1/guides/building/linux#limitations
- You need to use 64-bit systems, else the app might crash because it's using memory improvements that only work there

- The app is meant to be run as a "I'm the only app running on this system" app. This is important regarding RAM usage, because if you have much RAM, it will use much RAM. And if you, for some reason, try to limit the initially available RAM, the app might crash because it doesn't have the promised amount of RAM. (A future version might have a toggle for this)

## Step-by-step guide
Si prega di leggere l'intera guida una volta prima di iniziare ad eseguire ogni passaggio!

### 1. Scarica il repository
Questo passaggio è molto semplice, basta scaricare l'intero repository facendo clic sul pulsante verde sulla homepage di questo repository. Facoltativamente, è anche possibile scaricare codice specifico per una Rilascio, visitando la pagina Rilasciare e scaricare il file `.zip` negli asset. Un'altra cosa che potresti voler fare è [clonare questo repository](https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository)

### 2. Installa Rust
Uno dei requisiti per compilare il progetto è quello di avere installato Rust. Also make sure that your Rust installation is up-to-date with the command `rustup update`. You can check if you have Rust installed on your machine with the command `rustc --version`, if this command fails, head over to the [Rust website](https://www.rust-lang.org/tools/install) and follow the instructions for your OS.

### 3. Install NPM
NPM è necessario perché il frontend dell'applicazione funziona su JavaScript ed è fondamentalmente un sito web. Per verificare se hai già installato Node.js, prova ad eseguire i comandi: `node -v` e `npm -v`. Se qualcuno di loro fallisce, o si scopre che hai una versione più vecchia, vai sul sito web [NPM](https://docs.npmjs.com/cli/v7/configuring-npm/install) per installare l'ultima versione per il tuo sistema operativo. Se stai usando un WSL, [questa guida](https://learn.microsoft.com/en-us/windows/dev-environment/javascript/nodejs-on-wsl) potrebbe essere utile per te.

### 4. Installa Next.js
Il frontend è costruito su JavaScript utilizzando un noto framework chiamato [Next.js](https://nextjs.org), rende lo sviluppo del sito Web più veloce ed efficiente. Sarà necessario installare anche questo strumento per essere in grado di costruire l'applicazione. Ma non ti preoccupare, puoi farlo facilmente con NPM: `npm install next@latest react@latest react-dom@latest eslint-config-next@latest`. Questo installerà Next.js, React (che Next.js è basato su) e ESLint. Puoi saperne di più sul processo di installazione [qui](https://beta.nextjs.org/docs/installation).

### 5. Installa Tauri
Tauri è il framework che collega il backend Rust con il frontend Next.js. Si tratta di un progetto open-source realizzato da persone molto cordiale e accogliente. Unfortunately, installing Tauri is not as straightforward as other processes. È molto dipendente dal sistema operativo, e quindi ti assicurerai di soddisfare i [Prerequisiti](https://tauri.app/v1/guides/getting-started/prerequisites) prima di iniziare. In seguito, è possibile installare Tauri suing cargo: `cargo install tauri-cli`. Si potrebbe anche utilizzare NPM per installarlo, ma lavoreremo principalmente con il carico in questa breve guida. Scopri [la loro sezione FAQ](https://tauri.app/v1/guides/faq#node-or-cargo) per sapere perché NPM potrebbe essere migliore per te.

### 6. Installa dipendenze progetto
In primo luogo, installeremo i moduli del nodo. Per fare questo, vai alla directory che contiene tutto il codice Raspirus. Apri la directory `app` , apri un terminale in questa posizione ed esegui il comando: `npm install`. Questo potrebbe richiedere un po ', ma scaricherà tutti i moduli necessari. **Warning!:** On WSL you might get an `OpenSSL is missing` error, to fix this you need to edit the file `app/src-tauri/Cargo.toml` and add the following line: `openssl-sys = {version = "0.9.66", features = ["vendored"]}` in the `[dependencies]` section.

### 7. Genera il progetto
Prima di poter costruire completamente il progetto, c'è ancora una cosa che potresti voler controllare. To make sure that the Rust part of the project works fine, open the folder `app/src-tauri/` and execute the command `cargo build`. Se questo comando ha successo, puoi tornare alla directory `app/`. Se questo comando fallisce, si prega di [aprire un problema](https://github.com/Raspirus/Raspirus/issues/new) su questo repository con il maggior numero possibile di informazioni sull'errore. Se tutto è andato bene, ora sei nella directory `app` , ed è possibile eseguire in modo sicuro il comando `cargo tauri build`. Questo comando genererà l'intera applicazione e mostrerà un percorso alla fine del processo mostrandoti dove si trova l'eseguibile. Per impostazione predefinita, dovresti essere in grado di trovarlo nella cartella `app\src-tauri\target\release`.

## Conclusione
Questa applicazione è fondamentalmente un sito web collegato ad alcuni codici Rust e confezionato con il framework Tauri. Sarà quindi necessaria una sovrapposizione grafica per avviare e visualizzare il sito web. Questo progetto è in costante sviluppo e quindi, se si trova qualcosa di insolito, avere alcune buone idee o trovare alcuni errori, non abbiate paura di aprire un problema su questo repository e sarò felice di aiutarvi.
