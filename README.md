# Help Ticket

## Created with Laravel 10

#### Overview
1. User can create a new help ticket
2. Admin and user (owner) can comment on help ticket
3. Admin can reject or resolve the ticket
4. When admin update on the ticket, user will be notified via email that the ticket status has been updated.
5. User can give ticket title and description
6. User can upload a document (PDF or image)

#### Table Structure
1. Tickets 
    - title(string) {required}
    - description(text) {required}
    - attachment(string) {nullable}
    - user_id {required} - auto filled
    - status_changed_by_id {nullable}
    - status(open {default}, resolved, rejected)

2. Comments 
    - comment(text) {required}
    - user_id {required} - auto filled
    - ticket_id {required} - auto filled

#### Added Features
- Filter tickets list based on status
- Pagination

#### Todo
- Notification when user's ticket has new comment
- Cards showing total tickets, open tickets, resolved and rejected tickets