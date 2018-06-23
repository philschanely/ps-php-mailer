# Simple PHP Mail API

The script contained here allows you to set up a very simple HTML email sender using PHP's built in `mail()` function.

## Setup

Download the provided files and place or upload them into the desired directory on a PHP server. Ensure that the location is a url that can be called something like `http://yourdomain.com/mailer`.

### Authentication code setup

If you want to use an authentication code to increase the security of the script, ensure that `$require_auth` is set to `true`:

```php
// Indicate whether or not an authorization code is required
$require_auth = false;
```

If you decide to enable this then you must also create a file in the same directory called `env.php`. Copy and paste the contents of `env.sample.php` into your new `env.php` file. Then update the value stored in `AUTH` to include your own unique authorization code such as an [MD5 hash](http://www.miraclesalad.com/webtools/md5.php).

## API

This script must be called using a POST request that passes the following parameters:

| Parameter | Purpose |
|:--|:--|
| `to` | The email address to which the message should be sent. |
| `from` | The email address from which the message will appear to have been sent. |
| `subject` | The subject line for the email message. |
| `message` | The HTML formatted message to be included in the email. |
| `auth` | *(Optionally)* The matching authorization code set in `env.php` as described above. |

## Example Client Code

This script is intended to be callable from a client-side application such as one written in JavaScript. Using the [`axios`](http://localhost) library a well-formed request can be sent and handled like this:

```js
let axios = require('axios');
axios.post('http://yourdomain.com/mailer', {
  auth: 'YOUR_AUTH_CODE',
  to: 'myeamil@example.com',
  from: 'youremail@elsexample.com',
  subject: 'Test message',
  message: '<p>Hello!</p><p>We are glad you have decided to try out Mailer. We hope it works <strong>really</strong> well for you!</p>'
}).then(data => {
  if (data.response === 'SUCCESS') {
    console.log('Message was successfully sent to the mailer api.');
  } else {
    console.error('An error was returned by the mailer api:' . data.response);
  }
}).catch(err) {
  console.error('An error occurred with the mailer api request', err.message);
};
```
