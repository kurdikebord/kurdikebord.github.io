---
comments: true
---

# FAQ

???+ question "App crashes when updating"
    On Windows, it has been observed that the app crashes when attempting to update the database. We are aware of this issue and actively working to resolve it. The problem arises because the update function requires administrative privileges, which Windows does not automatically provide. To temporarily resolve this issue, you can execute the app with administrative privileges. Right-click on the app and select "Run as administrator" to launch it with the necessary privileges.

???+ question "Where does the Raspirus logo come from?"
    The logo of the Raspirus app features a red monster named Stuart, who is designed to represent a virus-eating creature. The logo was generated using [DALL-E](https://openai.com/product/dall-e-2), along with creative image editing and merging. Stuart is a friendly monster, except when he's hungry for viruses. You can find additional media and documents in the [dedicated repository](https://github.com/Raspirus/media). Feel free to use these images to create your own artwork and share them in the [discussion boards](https://github.com/orgs/Raspirus/discussions).

???+ question "My VScode setup is giving me issues"

    The Rust Analyzer plugin in Visual Studio Code searches for a `Cargo.toml` file in the current directory or its parent directory. To address this issue, you can add an option to the plugin settings and specify the location of your `Cargo.toml` file.

    As mentioned in [this comment](https://github.com/rust-lang/rust-analyzer/issues/2649#issuecomment-691582605), you can add the following lines to the end of your plugin settings JSON. Afterward, restart the Rust Analyzer for the modifications to take effect.

    ```json
    {
        "rust-analyzer.linkedProjects": [
            "/home/stuart/raspirus/raspirus/Cargo.toml"
        ]
    }
    ```

???+ question "Can't select directories/files"
    Unfortunately, as of [this issue](https://github.com/tauri-apps/tauri/issues/5405) with Tauri, we currently can't allow users to select both files and folders. To switch between selecting a single file or folder, you need to change it in the Raspirus settings. There you will find a switch for it.

???+ question "What is obfuscated mode?"
    Raspirus was originally intended for enterprise usage and therefore needed to be privacy-friendly. To ensure that, it added the "Obfuscation Mode", which will hide everything, detect malware faster and only display: "Malware found" or "No malware found". It is on by default, so if you want to know a bit more about your scan, you should probably deactivate it. You can do so in the settings.

???+ question "error: found a virtual manifest instead of a package manifest
    If you get this error when performing `cargo install` or using the Makefile, please note that it's a [know issue](https://github.com/rust-lang/cargo/issues/7599). The solution is simple, as explained on the [this](https://stackoverflow.com/a/76271890) Stackoverflow answer, simply change the command to include the workspace, like this: `cargo install --path src-tauri/`