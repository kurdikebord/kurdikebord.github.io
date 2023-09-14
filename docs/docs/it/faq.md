---
comments: vero
---

# FAQ

## Come posso generare la documentazione per questo repository?

You can find the generated documentation in the [rust folder](/rust/) and in case you want to generate your own, you can do so by using the `cargo doc` command. Ecco alcuni parametri che potresti voler utilizzare con esso:
- `--no-deps`: Ignora le dipendenze, documenta solo il codice stesso
- `--release`: Generalmente è meglio di un debug
- `--target-dir`: Where to output the docs All together, the command might look something like this: \
  `cargo doc --no-deps --release --target-dir=/docs/generated/`

## In VSCode, come posso impostare l'analizzatore di Rust per lavorare nella struttura di directory non standard?
Il plugin dell'analizzatore Rust in Visual Studio Code cerca di cercare un file Cargo.toml nella directory corrente o nella directory principale. But since we packed the entire application in the `app` directory, it's unable to find the file and therefore might not work. This is a big lost, as it doesn't tell you if your Rust files have correct syntax or not. Per risolvere questo problema, è possibile aggiungere un'opzione al plugin e specificare la posizione del file Cargo.toml. As stated [in this comment](https://github.com/rust-lang/rust-analyzer/issues/2649#issuecomment-691582605), you need to add the following lines to the end of your plugin settings' JSON. In seguito, dovrai anche riavviare l'analizzatore per rendere effettiva la modifica.

```json
{
    "rust-analyzer.linkedProjects": [
        "/home/matklad/tmp/hello/Cargo.toml"
    ]
}
```

## Più presto in arrivo
...
