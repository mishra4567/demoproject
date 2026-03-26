# 🌿 Git Branch System Guide (Laravel Project)

A complete and clean guide to using Git branches in your project.

---

## 📌 What is a Branch?

A **branch** is a separate workspace that allows you to:

* Build new features
* Fix bugs
* Test safely

Without affecting the main code.

---

## 🧱 Recommended Branch Structure

```
main        → Production (Live)
develop     → Testing / Staging
feature/*   → New Features
bugfix/*    → Bug Fixes
```

---

## 🚀 Basic Git Commands

### 🔹 Check Current Branch

```bash
git branch
```

### 🔹 Create New Branch

```bash
git checkout -b feature/media-system
```

### 🔹 Switch Branch

```bash
git checkout main
```

### 🔹 Go Back to Previous Branch

```bash
git checkout -
```

---

## 💻 Work on a Feature

```bash
git checkout -b feature/product-upload
git add .
git commit -m "Add product upload feature"
git push -u origin feature/product-upload
```

---

## 🔀 Merge Branch

### Step 1: Switch to main

```bash
git checkout main
```

### Step 2: Pull latest code

```bash
git pull origin main
```

### Step 3: Merge branch

```bash
git merge feature/product-upload
```

---

## 🧹 Delete Branch

```bash
git branch -d feature/product-upload
```

---

## 🔄 Update Existing Branch

```bash
git add .
git commit -m "Update feature"
git push
```

---

## 🔥 Best Workflow

1. Create a branch
2. Work on feature
3. Commit changes
4. Push branch
5. Merge into main

---

## ⚠️ Important Rules

* ❌ Do NOT work directly on `main`
* ✔ Always create a new branch
* ✔ Always pull latest before merging

```bash
git pull origin main
```

---

## 🌍 GitHub Workflow

1. Push branch to GitHub
2. Open repository
3. Click **Compare & Pull Request**
4. Review and Merge

---

## 📁 Example Project Structure

```
main
develop
feature/media
feature/product-attr
feature/admin-panel
bugfix/sku-error
```

---

## 💡 Pro Tips

* Use clear branch names
* Keep commits small
* Write meaningful commit messages
* Delete unused branches

---

## 🎯 Quick Commands Cheat Sheet

```bash
git branch
git checkout -b feature/name
git checkout main
git add .
git commit -m "message"
git push
git merge feature/name
git branch -d feature/name
```

---

## 🚀 Done!

Now you can manage your Laravel project using Git branches like a pro 🔥
