# BMS API Documentation

**Base URL**: `http://localhost:8000/api`

## Authentication
**Headers**: `Accept: application/json`

### Login
- **Method**: `POST`
- **Endpoint**: `/auth/login`
- **Body** (JSON):
  ```json
  {
    "email": "admin@bms.com",
    "password": "password"
  }
  ```
- **Response**: Returns `access_token`, `token_type`, `expires_in`, `user`.

### Get User Profile
- **Method**: `POST`
- **Endpoint**: `/auth/me`
- **Headers**: `Authorization: Bearer <token>`

### Refresh Token
- **Method**: `POST`
- **Endpoint**: `/auth/refresh`
- **Headers**: `Authorization: Bearer <token>`

### Logout
- **Method**: `POST`
- **Endpoint**: `/auth/logout`
- **Headers**: `Authorization: Bearer <token>`

---

## Admin Modules
**Headers**: 
- `Accept: application/json`
- `Authorization: Bearer <admin_token>`

### 1. Tenants
#### List Tenants
- **Method**: `GET`
- **Endpoint**: `/tenants`

#### Create Tenant
- **Method**: `POST`
- **Endpoint**: `/tenants`
- **Body** (JSON):
  ```json
  {
    "name": "Sunrise Real Estate"
  }
  ```

#### Show Tenant
- **Method**: `GET`
- **Endpoint**: `/tenants/{id}`

#### Update Tenant
- **Method**: `PUT` or `PATCH`
- **Endpoint**: `/tenants/{id}`
- **Body** (JSON):
  ```json
  {
    "name": "Sunrise Real Estate Updated"
  }
  ```

#### Delete Tenant
- **Method**: `DELETE`
- **Endpoint**: `/tenants/{id}`

### 2. House Owners
#### List House Owners
- **Method**: `GET`
- **Endpoint**: `/house-owners/list`

#### Create House Owner
- **Method**: `POST`
- **Endpoint**: `/house-owners`
- **Body** (JSON):
  ```json
  {
    "tenant_id": "uuid-of-tenant",
    "name": "John Owner",
    "email": "owner@example.com",
    "password": "password"
  }
  ```

#### Update House Owner
- **Method**: `PUT`
- **Endpoint**: `/house-owners/{id}`
- **Body** (JSON):
  ```json
  {
    "name": "Updated Name",
    "email": "updated@example.com",
    "password": "newpassword"
  }
  ```

### 3. Renters
#### List Renters
- **Method**: `GET`
- **Endpoint**: `/renters`

#### Show Renter
- **Method**: `GET`
- **Endpoint**: `/renters/{id}`

#### Create Renter
- **Method**: `POST`
- **Endpoint**: `/renters`
- **Body** (JSON):
  ```json
  {
    "tenant_id": "uuid-of-tenant",
    "name": "Alice Renter",
    "email": "renter@example.com",
    "password": "password"
  }
  ```

#### Assign Renter to Flat
- **Method**: `POST`
- **Endpoint**: `/assign-renter`
- **Body** (JSON):
  ```json
  {
    "flat_id": "uuid-of-flat",
    "renter_id": "uuid-of-renter"
  }
  ```

#### Update Renter
- **Method**: `PUT`
- **Endpoint**: `/renters/{id}`
- **Body** (JSON):
  ```json
  {
    "name": "Updated Name",
    "email": "updated@example.com",
    "password": "newpassword"
  }
  ```

---

## House Owner Modules
**Headers**: 
- `Accept: application/json`
- `Authorization: Bearer <owner_token>` (Must belong to correct Tenant)

### 1. Buildings
#### List Buildings
- **Method**: `GET`
- **Endpoint**: `/buildings`

#### Create Building
- **Method**: `POST`
- **Endpoint**: `/buildings`
- **Body** (JSON):
  ```json
  {
    "name": "Sunset Tower",
    "address": "123 Main St"
  }
  ```

#### Show/Update/Delete Building
- **Methods**: `GET`, `PUT`, `DELETE`
- **Endpoint**: `/buildings/{id}`

### 2. Flats
#### List Flats
- **Method**: `GET`
- **Endpoint**: `/flats`

#### Create Flat
- **Method**: `POST`
- **Endpoint**: `/flats`
- **Body** (JSON):
  ```json
  {
    "building_id": "uuid-of-building",
    "number": "101"
  }
  ```

#### Show/Update/Delete Flat
- **Methods**: `GET`, `PUT`, `DELETE`
- **Endpoint**: `/flats/{id}`

### 3. Bill Categories
#### List Categories
- **Method**: `GET`
- **Endpoint**: `/bill-categories`

#### Create Category
- **Method**: `POST`
- **Endpoint**: `/bill-categories`
- **Body** (JSON):
  ```json
  {
    "name": "Electricity"
  }
  ```

#### Show/Update/Delete Category
- **Methods**: `GET`, `PUT`, `DELETE`
- **Endpoint**: `/bill-categories/{id}`

### 4. Bills
#### List Bills
- **Method**: `GET`
- **Endpoint**: `/bills`

#### Create Bill
- **Method**: `POST`
- **Endpoint**: `/bills`
- **Body** (JSON):
  ```json
  {
    "flat_id": "uuid-of-flat",
    "category_id": "uuid-of-category",
    "amount": 150.50,
    "due_date": "2025-12-31",
    "status": "unpaid"
  }
  ```

#### Update Bill (Pay)
- **Method**: `PUT`
- **Endpoint**: `/bills/{id}`
- **Body** (JSON):
  ```json
  {
    "status": "paid"
  }
  ```
- **Note**: Modifying status to 'paid' triggers the `BillPaidJob`.

#### Show/Delete Bill
- **Methods**: `GET`, `DELETE`
- **Endpoint**: `/bills/{id}`
