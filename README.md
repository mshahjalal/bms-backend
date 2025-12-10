# Multi-Tenant Building Management System (BMS)

A complete backend API for managing a multi-tenant building system, built with Laravel, PostgreSQL (Citus), and Docker.

## Tech Stack
- **Framework**: Laravel 11 (PHP 8.3)
- **Database**: PostgreSQL 16 (Citus enabled)
- **Cache/Queue**: Redis
- **Auth**: JWT (Stateless)
- **Containerization**: Docker & Docker Compose

## Setup Instructions

1. **Clone & Start**:
   ```bash
   git clone <repo>
   cd BMS
   docker compose up -d --build
   ```

2. **Install Dependencies**:
   ```bash
   docker compose exec app composer install
   ```

3. **Setup Environment**:
   ```bash
   docker compose exec app cp .env.example .env
   # Ensure DB_HOST=db, DB_CONNECTION=pgsql, REDIS_HOST=redis
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan jwt:secret
   ```

4. **Migrate & Seed**:
   ```bash
   docker compose exec app php artisan migrate --seed
   ```

5. **Run Tests**:
   ```bash
   docker compose exec app php artisan test
   ```

## Scalability: Handling 1 Million Users

To scale this system to 1 million users, we leverage the architecture already in place:

1. **Database Sharding (Citus)**:
   - The core bottleneck is usually the database. We use **Citus** to distribute data across multiple nodes.
   - **Tenant-based Sharding**: Since queries are scoped by `tenant_id`, we can shard all major tables (`buildings`, `flats`, `bills`, `users`) by `tenant_id`. This ensures that queries for a specific tenant hit a specific node, allowing horizontal scaling of the database layer.

2. **Read/Write Splitting**:
   - For high-read traffic (e.g., millions of renters checking bills), we can use **Read Replicas** of the Postgres nodes.
   - Laravel supports read/write separation configuration out of the box.

3. **Caching (Redis)**:
   - Frequently accessed data (Auth tokens, Configs, Bill Categories) is cached in Redis to reduce DB load.
   - Rate Limiting uses Redis to efficiently handle high throughput without hitting the DB.

4. **Asynchronous Processing (Queues)**:
   - Email notifications and heavy calculations are offloaded to **Redis Queues**.
   - We can scale the number of **Queue Workers** horizontally (e.g., Kubernetes HPA) to process millions of jobs without blocking the API.

5. **App Scaling**:
   - The API is stateless (JWT). We can run dozens of `app` containers behind a Load Balancer (Nginx/AWS ALB) to handle the 1M users' request volume. 
   - For **Deployment** using **Kubernetes** is recommended, with **Cloude Control Manager (CCM)** for Load Balancing.

This "Shared-Nothing" architecture combined with Database Sharding ensures linear scalability.

## PRD and Technical Documentation

### 1. Project Overview
This Multi-Tenant Building Management System (BMS) is a robust backend API designed to handle property management operations for multiple tenants (Property Management Companies) simultaneously. It ensures strict data isolation while providing shared infrastructure for scalability.

### 2. Key Features
- **Multi-Tenancy**: Built-in data isolation using `tenant_id` and Global Scopes. Each user and resource belongs to a specific tenant.
- **Role-Based Access Control (RBAC)**:
  - **Super Admin**: Manages Tenants and House Owners.
  - **House Owner**: Manages Buildings, Flats, Bills, and Categories.
  - **Renter**: View-only access (assigned to flats).
- **Billing System**: Automated bill generation and tracking (Paid/Unpaid statuses).
- **Notifications**: Asynchronous email notifications (via Redis Queues) for Bill Creation and Payment confirmations.
- **Security**:
  - **JWT Authentication**: Stateless and secure API access.
  - **Idempotency**: Prevents duplicate transactions using unique keys.
  - **Rate Limiting**: Protects against abuse.

### 3. API Documentation
**Base URL**: `http://localhost:8000/api`

#### Authentication
All protected routes require a Bearer Token.
- **Login**: `POST /auth/login`
  - **Body**: `{"email": "admin@bms.com", "password": "password"}` (Default Super Admin)
  - **Response**: Returns `access_token` and `user` details.

#### Example: Create Tenant (Admin Only)
- **Endpoint**: `POST /tenants`
- **Headers**:
  - `Authorization`: `Bearer <your_token>`
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`
- **Body**:
  ```json
  {
    "name": "Sunrise Estates"
  }
  ```
- **Response (201 Created)**:
  ```json
  {
    "id": "uuid-string",
    "name": "Sunrise Estates",
    "created_at": "..."
  }
  ```

### 4. Technical Architecture
The system follows a clean, modular architecture:
- **Service Pattern**: Business logic is encapsulated in `app/Services` (e.g., `TenantService`, `BillService`) to keep Controllers skinny.
- **Form Requests**: validation rules are centralized in `app/Http/Requests`.
- **Jobs & Queues**: Heavy tasks like sending emails are offloaded to background workers using Redis.
- **Database Design**:
  - Normalized schema with Foreign Keys and Indexes.
  - Prepared for Horizontal Sharding using **Citus** (PostgreSQL extension).