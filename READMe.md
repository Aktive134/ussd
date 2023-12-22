# Bivety Bank APP (USSD)
This application simulates a ussd process, where users can make transactions via USSD.

# Database Design
- Entities
* User
* Agent
* Transactions
* Ussdsession

- Relationship
* User makes transaction
* Transaction invovles Agent/User
* Ussdsession belongs to User

- Entities data
* User: (uid, name, pin, phone, balance, registeredOn)
* Agent: (aid, name, agentNumber)
* Transaction: (tid, amount, uid, aid, ruid, ttype, completedOn)
* ussdsession: (sid, sessionId, ussdLevel, completed, uid)

Description:
- uid: User Id
- aid: Agent Id
- tid: Transaction Id
- sid: session Id
- ruid: receiver Id
- ttype: withdraw (withdraw), send money (send)

# Extras 
The ussd 3rd party used is Africastalking:
ussd documentation: https://developers.africastalking.com/docs/ussd/overview
sms documentation: https://developers.africastalking.com/docs/sms/overview

- Testing of the application process was achieved through ngrok.
- You will need to get a link via ngrok and utilize it as your callback url to the app on AT sandbox.
- Util.php file is exposed on purpose, it contains basic static information and apiKey for sms.
