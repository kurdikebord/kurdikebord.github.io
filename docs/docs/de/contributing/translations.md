---
comments: wahr
---

# Übersetzungen
Übersetzungen sind sehr wichtig und können anderen Menschen helfen, die App einfacher zu verstehen und sie umfassender zur Verfügung zu stellen. If you know a language outside of English and would like to add your translations you can do so in two ways: The first one is to translate the documentation, the second one is to translate the frontend strings.


## Code wird übersetzt
Es handelt sich um eine laufende Arbeit, die jedoch im Wesentlichen auf eine von zwei möglichen Wegen erfolgen wird:

### Übersetzungsdatei
Es gibt eine Übersetzungsdatei im JSON-Format für jede einzelne Sprache. So the app would load the strings it needs from that file. This is a bit easier to implement for the developers, but not very efficient or great for translators, as it involves forking the repository.

### Übersetzungsdienst
Wir verwenden einen externen Übersetzungsdienst, um das Projekt zu übersetzen. This option would allow translators to just translate the given strings and have an overview of how much has been translated and what still needs to be translated. Dies wäre die bevorzugte Option, aber die Kehrseite ist das Setup für diese Option. Since we don't know which service to use yet, this option will be added in the future when we have more concrete ideas and hopefully a bigger team. Wir werden wahrscheinlich [Crowdin](https://crowdin.com/) verwenden

## Übersetzungen von Docs
The docs you are reading right now also need translations and will require a much bigger effort than translating strings used on the frontend. Die Dokumentation ist ziemlich groß und kann daher einige Zeit in Anspruch nehmen, um zu übersetzen. Als obige Option könnten wir den Service auch hier nutzen, aber das wird in Zukunft hinzugefügt werden. If you want to start adding translations right away, I would suggest to do the following:

1. [Fork this repository](https://github.com/Raspirus/docs/fork): You can follow a [guide](https://docs.github.com/en/get-started/quickstart/fork-a-repo) online on how to do it properly.
2. Enter the `docs` folder and, if the language you want to translate doesn't exist yet, add a new directory whose name is the name of the language you want to write translations for. For example, it would be `it` for italian, `de` for german and so on.
3. Then change to that directory and edit the Markdown files inside that directory. Make sure you keep the original folder structure.
4. Nachdem Sie die Bearbeitung abgeschlossen haben, speichern Sie Ihre Dateien und laden sie auf GitHub in Ihren Fork hoch. Mache einen [GitHub Commit](https://docs.github.com/en/desktop/contributing-and-collaborating-using-github-desktop/making-changes-in-a-branch/committing-and-reviewing-changes-to-your-project)
5. Jetzt können Sie eine [Pull-Request](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/creating-a-pull-request) in das ursprüngliche Projektarchiv öffnen und verlangen, dass Ihre Änderungen dem Hauptprojekt hinzugefügt werden.
6. Jemand des Teams wird die Übersetzungen prüfen und wenn akzeptiert, diese dem Hauptprojekt hinzufügen.

In the future we hopefully have an external service that handles all this for you, so that you can focus on your translations without distractions. Wir werden wahrscheinlich [Crowdin](https://crowdin.com/) verwenden.