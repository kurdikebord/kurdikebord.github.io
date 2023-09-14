---
comments: vero
---

# Traduzioni
Le traduzioni sono molto importanti e possono aiutare altre persone a capire l'app più facile e renderla più ampia. If you know a language outside of English and would like to add your translations you can do so in two ways: The first one is to translate the documentation, the second one is to translate the frontend strings.


## Tradurre il codice
Si tratta di un lavoro in corso, ma essenzialmente sarà realizzato in uno dei due modi possibili:

### File di traduzione
C'è un file di traduzione in formato JSON per ogni lingua differente. So the app would load the strings it needs from that file. This is a bit easier to implement for the developers, but not very efficient or great for translators, as it involves forking the repository.

### Servizio di traduzione
Utilizziamo un servizio di traduzioni esterne per tradurre il progetto. This option would allow translators to just translate the given strings and have an overview of how much has been translated and what still needs to be translated. Questa sarebbe l'opzione preferita, ma il lato negativo è la configurazione di questa opzione. Since we don't know which service to use yet, this option will be added in the future when we have more concrete ideas and hopefully a bigger team. Probabilmente useremo [Crowdin](https://crowdin.com/)

## Tradurre Documenti
The docs you are reading right now also need translations and will require a much bigger effort than translating strings used on the frontend. La documentazione è abbastanza grande e può quindi richiedere un bel po' di tempo per tradurre. Come opzione di cui sopra, potremmo utilizzare il servizio anche qui, ma questo sarà aggiunto in futuro. If you want to start adding translations right away, I would suggest to do the following:

1. [Fork this repository](https://github.com/Raspirus/docs/fork): You can follow a [guide](https://docs.github.com/en/get-started/quickstart/fork-a-repo) online on how to do it properly.
2. Enter the `docs` folder and, if the language you want to translate doesn't exist yet, add a new directory whose name is the name of the language you want to write translations for. For example, it would be `it` for italian, `de` for german and so on.
3. Then change to that directory and edit the Markdown files inside that directory. Make sure you keep the original folder structure.
4. Dopo aver completato la modifica, salvare i file e caricarli sul tuo fork su GitHub. Fondamentalmente fai un commit [di GitHub](https://docs.github.com/en/desktop/contributing-and-collaborating-using-github-desktop/making-changes-in-a-branch/committing-and-reviewing-changes-to-your-project)
5. Ora puoi aprire una [pull-request](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/creating-a-pull-request) al repository originale e richiedere che le tue modifiche vengano aggiunte al progetto principale.
6. Qualcuno del team esaminerà le traduzioni e, se accettato, le aggiungerà al progetto principale.

In the future we hopefully have an external service that handles all this for you, so that you can focus on your translations without distractions. Probabilmente useremo [Crowdin](https://crowdin.com/).