<div align="center">
  <picture>
    <img src="title.png" alt="Title">
  </picture>
  <br>
  <b>Plugin for performing actions for donations</b>
  <br><br>
  <a href="https://poggit.pmmp.io/p/DonateExecute">
  	<img src="https://poggit.pmmp.io/shield.state/DonateExecute">
  </a>
</div>

## 🧾 Description:
Plugin for performing actions for donations. Some action will happen on the server if someone makes a donation to the author. Good entertainment for both streamers and its viewers.

## ✴️ Features:
- The ability to execute a command when making a donation, both on behalf of the player and on behalf of the console.
- Calling a special event that can be used by other plugins.
- Work thanks to the [**DonationAlerts**](https://www.donationalerts.com/) service.

## 📥 Installation:
1. Install this plugin on the server.

2. Create a `.env` file at the path `plugina_data/DonateExecute`.

3. Open the previously created file in a text editor, and paste `token = your token` into it. *(`your token` replace with your **DonationAlerts API** token.)*

4. Start the server and let the plugin verify the token. If the server started without errors, then you did everything correctly.

## 🛠️ For developers:
Using the [NewDonateEvent](src/Taskovich/DonateExecute/event/NewDonateEvent.php) event from this project, you will be able to perform actions in other plugins, which will expand the possibilities. You can see an example [here](example/ExamplePlugin/src/Az1ko/ExamplePlugin/Main.php).

## ⚠️ Warning:
The project is at the development stage. There may be errors and shortcomings. In the future, everything will be gradually corrected and updated.

## 🎯 Goals:
- [ ] Ability to process every incoming donation.
- [ ] Make the project much more efficient.
- [ ] In addition to [**DonationAlerts**](https://www.donationalerts.com/), add new services for accepting donations (with API support, of course).
