---
comments: wahr
---

# Installation
Diese Anleitung hilft Ihnen beim Aufbau des Projekts auf Ihrem eigenen Rechner.

## Inhaltsverzeichnis
- [Einführung](#introduction)
- [Einschränkungen](#limitations)
- [Step-by-step guide](#step-by-step-guide)
  - [1. Repository herunterladen](#1-download-the-repository)
  - [2. Rust installieren](#2-install-rust)
  - [3. Install NPM](#3-install-npm)
  - [4. Next.js installieren](#4-install-nextjs)
  - [5. Tauri installieren](#5-install-tauri)
  - [6. Projektabhängigkeiten installieren](#6-install-project-dependencies)
  - [7. Projekt erstellen](#7-build-the-project)

- [Conclusion](#conclusion) https://github.com/Raspirus/Raspirus/releases
## Einführung
For people that just want a working app, they can just head over to the [Release page](https://github.com/Raspirus/Raspirus/releases/latest) and download the executable for the correct platform. But if you are on a different Linux distribution, unsupported OS, or just want to compile the project on your own, this step-by-step guide will guide you.

## Einschränkungen
- Glibc kann unter Linux Probleme verursachen: https://tauri.app/v1/guides/building/linux#limitations
- You need to use 64-bit systems, else the app might crash because it's using memory improvements that only work there

- The app is meant to be run as a "I'm the only app running on this system" app. This is important regarding RAM usage, because if you have much RAM, it will use much RAM. And if you, for some reason, try to limit the initially available RAM, the app might crash because it doesn't have the promised amount of RAM. (A future version might have a toggle for this)

## Step-by-step guide
Bitte lesen Sie die ganze Anleitung einmal, bevor Sie jeden Schritt ausführen!

### 1. Repository herunterladen
Dieser Schritt ist sehr einfach, einfach das ganze Projektarchiv herunterladen, indem Sie auf die grüne Schaltfläche auf der Homepage dieses Projektarchivs klicken. Optional können Sie auch den für eine Veröffentlichung spezifischen Code herunterladen, indem Sie die Release-Seite besuchen und die `.zip` Datei in den Assets herunterladen. Eine weitere Sache, die Sie vielleicht tun möchten, ist [dieses Projektarchiv klonen](https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository)

### 2. Rust installieren
Eine der Voraussetzungen, um das Projekt zu kompilieren, ist die Installation von Rst. Also make sure that your Rust installation is up-to-date with the command `rustup update`. You can check if you have Rust installed on your machine with the command `rustc --version`, if this command fails, head over to the [Rust website](https://www.rust-lang.org/tools/install) and follow the instructions for your OS.

### 3. Install NPM
NPM wird benötigt, da das Frontend der App auf JavaScript funktioniert und im Grunde eine Webseite ist. Um zu überprüfen, ob Node.js bereits installiert ist, führen Sie die Befehle `node -v` und `npm -v` aus. Wenn einer von ihnen fehlschlägt, oder Sie feststellen, dass Sie eine ältere Version haben auf der [NPM-Website](https://docs.npmjs.com/cli/v7/configuring-npm/install) installieren, um die neueste Version für Ihr Betriebssystem zu installieren. Wenn du ein WSL verwendest, [könnte diese Anleitung](https://learn.microsoft.com/en-us/windows/dev-environment/javascript/nodejs-on-wsl) für dich nützlich sein.

### 4. Next.js installieren
Das Frontend basiert auf JavaScript mit dem bekannten Framework [Next.js](https://nextjs.org), es macht die Website-Entwicklung schneller und effizienter. Sie müssen auch dieses Tool installieren, um die Anwendung erstellen zu können. Aber keine Sorge, Sie können dies einfach mit NPM: `npm installieren next@latest react@latest react-dom@latest eslint-config-next@latest`. Dies wird Next.js, React (die Next.js basiert darauf) und ESLink installieren. Mehr über den Installationsprozess [erfahren Sie hier](https://beta.nextjs.org/docs/installation).

### 5. Tauri installieren
Tauri ist das Framework, das das Ruster Backend mit dem Next.js Frontend verbindet. Es ist ein Open-Source-Projekt von sehr freundlichen und gastfreundlichen Menschen. Unfortunately, installing Tauri is not as straightforward as other processes. Es ist sehr OS abhängig und Sie werden daher sicherstellen, dass Sie die [Voraussetzungen](https://tauri.app/v1/guides/getting-started/prerequisites) vor dem Start erfüllen. Danach können Sie Tauri-cli installieren: `Fracht installiert Tauri-cli`. Sie könnten auch NPM verwenden, um es zu installieren, aber wir werden hauptsächlich mit Fracht in dieser kurzen Anleitung arbeiten. Schau dir [in seinem FAQ-Bereich](https://tauri.app/v1/guides/faq#node-or-cargo) an, um zu erfahren, warum NPM für dich vielleicht besser ist.

### 6. Projektabhängigkeiten installieren
Zunächst werden wir die Knotenmodule installieren. Um dies zu tun, gehen Sie in das Verzeichnis, das den gesamten Raspirus-Code enthält. Öffnen Sie das `App` Verzeichnis, öffnen Sie ein Terminal an diesem Ort und führen Sie den Befehl `npm install` aus. Dies kann eine Weile dauern, aber es wird alle notwendigen Module herunterladen. **Warning!:** On WSL you might get an `OpenSSL is missing` error, to fix this you need to edit the file `app/src-tauri/Cargo.toml` and add the following line: `openssl-sys = {version = "0.9.66", features = ["vendored"]}` in the `[dependencies]` section.

### 7. Projekt erstellen
Bevor Sie das Projekt komplett erstellen können, gibt es noch eine Sache, die Sie vielleicht überprüfen möchten. To make sure that the Rust part of the project works fine, open the folder `app/src-tauri/` and execute the command `cargo build`. Wenn dieser Befehl erfolgreich ist, können Sie zum `app/` Verzeichnis zurückkehren. Wenn dieser Befehl fehlschlägt, öffnen Sie bitte [ein Problem](https://github.com/Raspirus/Raspirus/issues/new) in diesem Projektarchiv mit so vielen Informationen wie möglich. Wenn alles gut gelaufen ist, befinden Sie sich jetzt im `App` Verzeichnis, und Sie können sicher den Befehl `cargo tauri build` ausführen. Dieser Befehl erstellt die gesamte Anwendung und zeigt am Ende des Prozesses einen Pfad an, der Ihnen zeigt, wo sich die ausführbare Datei befindet. Standardmäßig sollten Sie es im `app\src-tauri\target\release` Ordner finden können.

## Schlussfolgerung
Diese Anwendung ist im Grunde eine Website, die mit einigen Rust Code verbunden und mit dem Tauri Framework verpackt ist. Es wird daher ein grafisches Overlay benötigen, um die Website zu starten und anzuzeigen. Dieses Projekt befindet sich in ständiger Entwicklung und wenn Sie etwas Ungewöhnliches finden, haben Sie einige gute Ideen oder finden Sie einige Fehler, haben Sie keine Angst, ein Problem auf diesem Repository zu öffnen und ich werde Ihnen gerne helfen.
