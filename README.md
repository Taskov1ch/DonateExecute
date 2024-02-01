<div align="center">
  <picture>
    <img src="image.png" alt="image or gif">
  </picture>
  <br>
  <b>Plugin for performing actions for donations</b>
</div>

## Description:
Plugin for performing actions for donations. Some action will happen on the server if someone makes a donation to the author. Good entertainment for both streamers and its viewers.

## Features:
- The ability to execute a command when making a donation, both on behalf of the player and on behalf of the console.
- Calling a special event that can be used by other plugins.
- Work thanks to the [**DonationAlerts**](https://www.donationalerts.com/) service.

## For developers:
Using the [NewDonateEvent](src/Taskovich/DonateExecute/event/NewDonateEvent.php) event from this plugin, you can perform actions in other plugins, which will expand the capabilities. You can see an example [here](example/ExamplePlugin/src/Az1ko/ExamplePlugin/Main.php).
