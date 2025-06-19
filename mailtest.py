import resend

resend.api_key = "re_cvNywNbY_KqsPYLW24FhZuKfekw6YXWM3"

params: resend.Emails.SendParams = {
  "from": "CECyAYUDA <verification@cecyayuda.lat>",
  "to": ["urielzuniga@outlook.es"],
  "subject": "hello world",
  "html": "<p>it works!</p>",
    "text": "it works!",
}

email = resend.Emails.send(params)
print(email)