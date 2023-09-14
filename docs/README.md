# Welcome to the Docs! 📚

[![Crowdin](https://badges.crowdin.net/raspirus/localized.svg)](https://crowdin.com/project/raspirus)
![Lines of code](https://img.shields.io/tokei/lines/github/Raspirus/docs?label=Lines%20of%20docs)

Thank you for contributing to the project documentation! This guide will walk you through the process of setting up the project, making changes, and contributing your translations.

## How to Get Started

### 1. Clone the Repository

To get started, clone the repository to your local machine using the following command:

```bash
git clone https://github.com/Raspirus/docs.git
```

### 2. Install Python Dependencies

Navigate to the project directory and install the required Python dependencies from the `requirements.txt` file using the following command:

```bash
pip install -r requirements.txt
```

### 3. Start the Project Locally

You can now start the project locally by running the following command:

```bash
mkdocs serve
```

This will launch a development server, and you can access the documentation by opening [http://localhost:8000](http://localhost:8000) in your web browser.

### 4. Making Changes

Feel free to make changes to the documentation according to your needs. The changes you make will be reflected in the locally served version of the documentation.

### 5. Creating a Pull Request

Once you've made your changes and are satisfied with them, it's time to contribute back to the project. Follow these steps to create a pull request:

1. Commit your changes:
   ```bash
   git add .
   git commit -m "Your descriptive commit message here"
   ```

2. Push your changes to your forked repository:
   ```bash
   git push origin main
   ```

3. Open a pull request:
   - Visit your forked repository on GitHub.
   - Click on the "Pull Requests" tab.
   - Click the "New Pull Request" button.
   - Select the main branch of the main repository as the base branch.
   - Select your forked repository's main branch as the compare branch.
   - Add a descriptive title and details about your changes.
   - Click "Create Pull Request".

## Adding Translations via Crowdin

We use Crowdin for translations to make our documentation accessible to a wider audience. To contribute translations, follow these steps:

1. Visit the [Crowdin project](https://crowdin.com/project/raspirus) page.
2. Log in or sign up for a Crowdin account.
3. Select the language you want to contribute to.
4. Translate the content directly on the Crowdin platform.
5. Your translations will be reviewed and integrated into the project.

## Built with MkDocs and Material Theme

This documentation was created with the power of [MkDocs](https://www.mkdocs.org/) and styled using the awesome Material Theme. The simplicity and elegance of MkDocs combined with the Material Theme's modern design make for an excellent documentation experience.

Feel free to explore, contribute, and help us improve these docs! 🚀

If you have any questions, need assistance, or want to discuss anything related to the project, don't hesitate to join our Discord community at [Discord](https://discord.gg/Vx7fW9PA8B). We're here to help and excited to connect with you!

Happy documenting! 📝
