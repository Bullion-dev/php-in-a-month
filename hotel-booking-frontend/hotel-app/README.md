# Hotel Booking System (React frontend)

    npm install
    npm run dev          # open http://localhost:5173 (use "localhost", not 127.0.0.1)

Demo mode (fake data, no backend): VITE_USE_MOCK=true in .env.
Real backend: set VITE_USE_MOCK=false and VITE_API_URL to your PHP folder, then restart npm run dev.

PHP side needs: CORS with Allow-Origin http://localhost:5173 + Allow-Credentials true, OPTIONS answered in db.php,
and login.php saving user_id, username, email, role in $_SESSION. See CHEATSHEET.md for the full map.
