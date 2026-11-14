# Quizzy

A real-time quiz application built with Laravel, Livewire, and Flux UI. Admins create quiz content, Hosts control live quiz sessions, Participants answer questions, and Viewers observe in read-only mode.

## Tech Stack

- **Laravel 13** — Backend framework
- **Livewire 4** — Real-time UI with polling (no WebSockets)
- **Flux UI** — Component library
- **Laravel Socialite** — OAuth authentication (Zoho + Microsoft)
- **Pest** — Testing framework

## Installation

1. **Clone the repository**

```bash
git clone <repository-url>
cd quizzy
```

2. **Install dependencies**

```bash
composer install
bun install
```

3. **Configure environment**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Set up the database**

```bash
php artisan migrate:fresh --seed
```

5. **Build assets**

```bash
bun run build
```

6. **Start the development server**

```bash
php artisan serve
```

## OAuth Configuration

Quizzy supports social sign-in via **Zoho** and **Microsoft**, in addition to email/password authentication. Follow the steps below to configure each provider.

### Zoho OAuth Setup

1. Go to the [Zoho Developer Console](https://accounts.zoho.com/developerconsole) and sign in.

2. Click **Add Client** and select **Confidential** (for web apps).

3. Fill in the following details:
   - **Client Name**: `Quizzy` (or your app name)
   - **Developer Name**: Your name or organization
   - **Response Type**: Select `Code` (Authorization Code flow)
   - **Scope**: Select `Account.read` (to retrieve user email and name)
   - **Redirect URL**: `http://localhost:8000/auth/zoho/callback` (update for production)

4. After creating the client, note the **Client ID** and **Client Secret**.

5. Add the following to your `.env` file:

```env
ZOHO_CLIENT_ID=your_zoho_client_id
ZOHO_CLIENT_SECRET=your_zoho_client_secret
ZOHO_REDIRECT_URI=http://localhost:8000/auth/zoho/callback
```

### Microsoft OAuth Setup

1. Go to the [Microsoft Entra ID Portal](https://portal.azure.com/#blade/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/RegisteredApps).

2. Click **New registration**.

3. Fill in the following details:
   - **Name**: `Quizzy` (or your app name)
   - **Supported account types**: Select **Accounts in any organizational directory and personal Microsoft accounts** (or choose based on your needs)
   - **Redirect URI**: Select **Web**, then enter `http://localhost:8000/auth/microsoft/callback`

4. After creating the app, note the **Application (client) ID**.

5. Go to **Certificates & secrets** → **New client secret** → add a description → click **Add** → copy the **secret value** immediately (it won't be shown again).

6. Add the following to your `.env` file:

```env
MICROSOFT_CLIENT_ID=your_microsoft_client_id
MICROSOFT_CLIENT_SECRET=your_microsoft_client_secret
MICROSOFT_REDIRECT_URI=http://localhost:8000/auth/microsoft/callback
```

### OAuth Routes

The following routes handle the OAuth flow:

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/auth/{provider}/redirect` | Redirects to the provider's authorization page |
| GET | `/auth/{provider}/callback` | Handles the OAuth callback and logs in the user |

Replace `{provider}` with `zoho` or `microsoft`.

### Production Setup

For production, update the redirect URIs in both your `.env` file and each provider's developer console to match your production domain:

```env
ZOHO_REDIRECT_URI=https://yourdomain.com/auth/zoho/callback
MICROSOFT_REDIRECT_URI=https://yourdomain.com/auth/microsoft/callback
```

After configuring credentials, clear the config cache:

```bash
php artisan config:clear
```

## Default Users

After seeding the database, the following users are available for testing (all with the password `password`):

| Role | Email |
|------|-------|
| Admin | `admin@quizzy.test` |
| Host | `host@quizzy.test` |
| Viewer | `viewer@quizzy.test` |
| Participant | 3 auto-generated users (check the `users` table for their emails) |

## Usage

### Admin
- Navigate to `/dashboard` to manage quizzes
- Create, edit, and delete quizzes with multiple-choice or true/false questions

### Host
- From the dashboard, click "Host" on any quiz to start a session
- Share the invite link with participants
- Control the quiz flow: show questions, reveal answers, navigate, and display scores

### Participant
- Join via the invite link
- Select "Join as Participant" to answer questions
- Submit one answer per question

### Viewer
- Join via the invite link
- Select "Join as Viewer" for read-only access
- The viewer page (`/session/{token}/viewer`) is public and does not require authentication, making it suitable for sharing in meetings or displaying on a big screen

## Testing

```bash
php artisan test
```

## License

MIT
