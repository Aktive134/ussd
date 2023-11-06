# Bivety Bank APP (USSD)
This application simulates a banking system, where users can make transactions via USSD.

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
