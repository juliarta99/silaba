# SILABA - Sistem Lapor Badung

---

## Tech Stack

* **Framework:** Laravel (v13)
* **Frontend Interactivity:** Livewire (v4)
* **Database:** MySQL
* **Styling:** Tailwind CSS

---

## Prerequisites

Before you begin, ensure you have the following installed:

* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL Server

---

## Installation Guide

Follow these steps to set up the project locally:

1. **Clone the repository:**
```bash
git clone https://github.com/juliarta99/silaba.git
cd silaba

```


2. **Install PHP dependencies:**
```bash
composer install

```


3. **Install Frontend dependencies:**
```bash
npm install

```


4. **Setup Environment:**
Copy the `.env.example` file to `.env`
```bash
cp .env.example .env

```


5. **Environment & Database Configuration:**
Open your `.env` file and configure the database credentials, application timezone, and third-party APIs.
**Database Configuration:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=silaba
DB_USERNAME=root
DB_PASSWORD=

```


**Application Timezone:**
Set the timezone according to your needs (e.g., `Asia/Makassar` or `Asia/Jakarta`).
```env
APP_TIMEZONE=Asia/Makassar

```


**Third-Party Services (Fonnte & Gemini):**
* **Fonnte (WhatsApp Gateway):** You can register and get your token at [https://fonnte.com/](https://fonnte.com/).
* **Google Gemini AI:** You can generate your API key at [Google AI Studio](https://aistudio.google.com/).


```env
FONNTE_TOKEN=your_fonnte_token_here
FONNTE_API_URL=https://api.fonnte.com/send

GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_MODEL=gemini-3.1-flash-lite

```


6. **Generate Application Key:**
```bash
php artisan key:generate

```


7. **Run Database Migrations:**
```bash
php artisan migrate

```


8. **Run Development Server:**
You can start the application using one of the following methods:
**Run Frontend and Backend simultaneously**
```bash
composer run dev

```


**Run Frontend and Backend separately**
Open two terminal windows:
**Terminal 1: Frontend**
```bash
npm run dev

```


**Terminal 2: Backend**
```bash
php artisan serve

```


9. **Access the Application**
Open your web browser and navigate to:
```bash
http://localhost:8000

```



---

## Troubleshooting

**Error: Database Connection**

* Make sure `MySQL/database` service is running
* Double-check the configuration in `.env` file
* Ensure the `database` has been created

**Error: NPM Dependencies**

If error occurs during `npm install`:

```bash
rm -rf node_modules package-lock.json
npm install

```

**Error: Composer Dependencies**

If error occurs during `composer install`:

```bash
rm -rf vendor composer.lock
composer install

```

**Let's Work!**