

# ⭐ Review Form API – Laravel RESTful Service

A simple and secure **Review Form API** built using **Laravel**, designed to allow users to submit, view, update, and delete product or service reviews.

---

## ✅ Features

* Submit and manage reviews (CRUD)
* RESTful API with structured JSON responses
* Authentication using **Laravel Sanctum**
* Input validation and error handling
* Rate with stars (1–5) and optional comments
* User-specific review management

---

## 🛠️ Tech Stack

| Technology         | Purpose                 |
| ------------------ | ----------------------- |
| **Laravel**        | Backend Framework (PHP) |
| **Breeze**        | API Authentication      |
| **MySQL**          | Relational Database     |
| **Postman / cURL** | API Testing Tool        |

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/review-form-api.git
cd review-form-api
```

### 2. Install Dependencies

```bash
composer install
```


### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Start the Server

```bash
php artisan serve
```

---


---

## 📑 API Endpoints

### 🔑 Auth Routes

| Method | Endpoint        | Description         |
| ------ | --------------- | ------------------- |
| POST   | `/api/register` | Register user       |
| POST   | `/api/login`    | Login and get token |
| POST   | `/api/logout`   | Logout              |

### ⭐ Review Routes (Authenticated)

| Method | Endpoint            | Description         |
| ------ | ------------------- | ------------------- |
| GET    | `/api/reviews`      | Get all reviews     |
| POST   | `/api/reviews`      | Create a new review |
| GET    | `/api/reviews/{id}` | Get a single review |
| PUT    | `/api/reviews/{id}` | Update a review     |
| DELETE | `/api/reviews/{id}` | Delete a review     |

> All review routes require a **Bearer token** in the Authorization header.

---

## 📄 Review JSON Format

```json
{
  "rating": 5,
  "comment": "Excellent service!",

}
```

![Screenshot 2025-05-11 171154](https://github.com/user-attachments/assets/5f55ab97-417a-4845-919d-ce51e2d7e6af)

![Screenshot 2025-05-11 171055](https://github.com/user-attachments/assets/08200340-c073-4056-b000-af4ecb82857f)
![Screenshot 2025-05-11 172736](https://github.com/user-attachments/assets/1b9f194b-f83e-40f2-940d-9e034c3a49dc)

## 📂 Folder Structure

```
/app
  /Models/Review.php
  /Http/Controllers/API/ReviewController.php
/routes
  api.php               # API routes
/database/migrations    # Reviews table schema
```

---
