# Who calls whom (for explaining the app)

How it fits together: React never touches the database. It calls a PHP file, the PHP file talks to MySQL and answers in JSON.
All calls go through `src/api.js`, and the list of PHP file names lives in `ENDPOINTS` at the top of that file.
Login is remembered by a PHP session cookie, which `credentials: 'include'` sends on every request.

| Screen | React file | PHP file(s) | What happens 
| Sign up | pages/AuthPage.jsx | register.php | Sends username, email, password, confirm_password. PHP hashes the password and saves the user. |
| Login | pages/AuthPage.jsx, auth.jsx | login.php | PHP checks password_verify, starts the session, returns id, name, email, role. Admin/staff go to /admin, others to /app. |
| Route protection | App.jsx (Guard) | auth-middleware.php | React hides pages by role. PHP does the real check on its side. |
| Admin dashboard | pages/Dashboard.jsx | dashboard-stats.php, get-bookings.php | Three counts, plus the five newest bookings. |
| Admin bookings | pages/AdminBookings.jsx | get-bookings.php | Joins bookings, customers and rooms. Search happens in the browser. |
| Admin rooms: list | pages/AdminRooms.jsx | get-rooms.php | Lists rooms. |
| Admin rooms: add / edit / delete | pages/AdminRooms.jsx | add-rooms.php, update-rooms.php, delete-rooms.php | Add and edit send FormData because of the photo. Delete sends the room id. |
| Customer: find a room | pages/Browse.jsx | get-rooms.php, available-rooms.php | All rooms first. After picking dates, only rooms with no overlapping booking. |
| Customer: book | pages/Browse.jsx | add-booking.php | Finds or creates the customer by email, looks up the price, nights x price = total_price, saves the booking as upcoming. |
| Customer: my bookings, home | pages/MyBookings.jsx, Dashboard.jsx | my-bookings.php | Bookings for the logged-in user's email (kept in the session at login). |

## Not built yet (honest "next steps")
Customers and Users management pages, Reports, Profile, cancelling a booking, a logout.php that destroys the session.
