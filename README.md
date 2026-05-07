# 🎓 Eduma - Learning Management System (LMS)

A full-stack Learning Management System built with Laravel, developed as an internal training project during my internship at Mirabo-Tech.

The system is designed to manage courses, lessons, users, payments, and learning analytics with a clean service-oriented architecture and scalable backend design.

---

## 🚀 Key Features

### 📚 Course Management
- Multi-level course structure (Course → Chapter → Lesson)
- Full CRUD operations for learning content
- Lesson ordering and hierarchical constraints
- Prevent deletion of chapters containing active lessons

### 👤 User & Access Control (RBAC)
- Role-Based Access Control (Admin, Instructor, Student)
- Permission-based middleware system
- Role hierarchy with granular access control
- Secure route protection

### 💳 Payment Integration
- Integrated **SePay payment gateway**
- Webhook handling for real-time payment updates
- Automatic enrollment after successful payment

### 🤖 AI Chatbot Integration
- Chatbot workflow automation using **n8n**
- User support and interaction automation
- Event-driven response system

### 📊 Tracking & Analytics
- User activity logging system (Create / Update / Delete actions)
- Learning progress tracking
- Study insight generation for user engagement analysis

### 📈 Recommendation System
- CV-based course recommendation API
- Matching user profile with relevant learning paths

### ⚡ Background Processing
- Queue system for heavy tasks (email, analytics, insights)
- Asynchronous job processing for performance optimization

---

## 🏗️ Architecture Overview

This project follows a **Service-Oriented Architecture (SOA)** approach:

- Controllers → handle HTTP requests only
- Services → contain all business logic
- Models → data layer abstraction
- Middleware → authorization & access control
- Jobs/Queues → background processing

Key design principles applied:
- Separation of concerns
- Modular service layer
- Reusable business logic
- Scalable architecture structure

---

## 🛠️ Tech Stack

### Backend
- PHP 8+
- Laravel Framework
- MySQL
- RESTful API

### Frontend
- Blade Templates
- JavaScript (ES6+)
- CSS

### DevOps / Tools
- Docker (containerization)
- Linux VPS deployment
- Nginx
- Git, GitHub
- Composer
- npm
- Postman

### Integrations
- SePay Payment API
- n8n workflow automation

---

## 🐳 Deployment

- Application containerized using **Docker**
- Deployed on a **Linux VPS**
- Configured Nginx as reverse proxy
- Environment managed using `.env` configuration

---

## 📊 My Contributions

As a Software Engineer Intern in a 2-member team, I was responsible for:

- Designing and implementing service-based backend architecture
- Building core LMS features (course, lesson, user management)
- Developing RESTful APIs for system functionality
- Implementing RBAC authorization system
- Integrating payment gateway (SePay)
- Developing chatbot automation workflow (n8n)
- Building activity tracking and analytics system
- Implementing queue-based background processing
- Deploying application on Docker-based VPS environment

---

## 💡 Key Learnings

- Building scalable backend architecture using Laravel
- Designing modular service-oriented systems
- Working with real-world integrations (payment, webhook, automation)
- Understanding system performance optimization using queues & caching concepts
- Deploying production-like applications on VPS

---

## 📌 Project Status

This is an internal training project completed during internship and is used for learning, system design practice, and portfolio demonstration purposes.

---

## 📬 Contact

If you want to discuss this project or my experience:

- GitHub: [mhieu1905]
- Email: [minhhieuleviet@gmail.com]

---