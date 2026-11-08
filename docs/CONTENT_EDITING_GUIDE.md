# UniGuide Pakistan — Content Editing Guide

Welcome to the **UniGuide Pakistan Content Editing Guide**.

All editable website copy (headings, descriptions, button labels, notifications, and navigation items) is stored inside clean **JSON files** in the `resources/content/` folder.

This guide explains how to edit these files safely without breaking anything.

---

## Table of Contents

1. [Understanding JSON in 2 Minutes](#1-understanding-json-in-2-minutes)
2. [Content File Directory](#2-content-file-directory)
3. [Safe Editing Rules (The Golden Rules)](#3-safe-editing-rules)
4. [Using Placeholders (Like :count and :year)](#4-using-placeholders)
5. [What You Should Edit vs What You Should NOT Edit](#5-what-you-should-edit-vs-what-you-should-not-edit)
6. [How to Validate Your JSON Before Saving](#6-how-to-validate-your-json)
7. [How to Restore Previous Content (If Something Breaks)](#7-how-to-restore-previous-content)

---

## 1. Understanding JSON in 2 Minutes

JSON (JavaScript Object Notation) is a simple way of organizing text into **Keys** and **Values**.

Think of a dictionary:
- **Key**: The name or label (DO NOT CHANGE).
- **Value**: The text shown on the website (YOU CAN CHANGE).

### Example:
```json
{
  "title": "Welcome to UniGuide Pakistan",
  "button": "Get Started"
}
```
In this example:
- `"title"` and `"button"` are **Keys**. Keep them exactly as they are.
- `"Welcome to UniGuide Pakistan"` and `"Get Started"` are **Values**. You can edit these freely.

---

## 2. Content File Directory

All editable content files live in:
`resources/content/`

| File | What Text Lives Here |
| :--- | :--- |
| `global.json` | Brand tagline, copyright, skip-to-content, footer column titles, global buttons |
| `navigation.json` | Public navbar links, student dashboard tabs, admin menu items |
| `homepage.json` | Homepage hero headline, subtitle, search tab titles, statistics counters, CTA banner |
| `about.json` | About page story, mission statements, statutory verification seal description |
| `how-it-works.json` | 4-step admissions roadmap, formula explainer headings |
| `features.json` | Platform feature cards, 1-Bill banking integration copy |
| `contact.json` | Inquiry form labels, placeholders, helpdesk addresses |
| `universities.json` | University directory header, filter labels, search placeholder, empty states |
| `programs.json` | Degree program catalog header, search bar, empty states |
| `admissions.json` | Admission cycle explorer header, calendar and checklist titles |
| `scholarships.json` | Financial aid explorer header, verified aid badge |
| `comparison.json` | Side-by-side comparison builder instructions and warning alerts |
| `eligibility.json` | Eligibility checker wizard step labels and evaluation tiers |
| `ai-counselor.json` | AI Counselor welcome message, question composer placeholder, disclaimer |
| `student.json` | Student dashboard welcome, profile completeness labels, tracker titles |
| `university-admin.json` | Institutional portal headings, KPI labels, queue empty states |
| `national-admin.json` | Superadmin operational command titles, crawler monitor headings |
| `authentication.json` | Sign in / register headings, input field labels, role switcher text |
| `messages.json` | Popups, confirmation dialogs, delete alerts, toast notifications |
| `seo.json` | Default page titles, Google meta descriptions, social media tags |

---

## 3. Safe Editing Rules

To ensure your changes load smoothly without syntax errors, follow these simple rules:

### Rule 1: Always Keep Quotes Around Text
Every piece of text must be enclosed in standard double quotes `"..."`.
- ✅ **Correct:** `"headline": "Find your university in Punjab"`
- ❌ **Wrong:** `"headline": Find your university in Punjab` (missing quotes)

### Rule 2: Watch the Commas
In JSON, every item in a list must end with a comma `,`, **except the very last item** in a section.
- ✅ **Correct:**
  ```json
  "first_item": "Hello",
  "second_item": "World"
  ```
- ❌ **Wrong:**
  ```json
  "first_item": "Hello"
  "second_item": "World"
  ```

### Rule 3: Do Not Change Key Names
Never change the left side of the colon `:`.
- ✅ **Correct:** `"sign_in": "Log In Now"` (changed value)
- ❌ **Wrong:** `"log_in_button": "Log In Now"` (changed key name)

### Rule 4: Escaping Quotes Inside Text
If you need to use a double quote inside your text, put a backslash `\` before it:
- ✅ **Correct:** `"quote": "Pakistan\'s top universities"` or `"quote": "Pakistan's top universities"`
- ✅ **Correct with double quotes:** `"quote": "Welcome to \"UniGuide\" today"`

---

## 4. Using Placeholders

Some strings contain dynamic placeholders like `:count`, `:year`, or `:name`.

### Examples:
1. `"institutions_listed": ":count Listed Institutions"`
   - On the website, `:count` is automatically replaced with the actual number of universities (e.g. `248 Listed Institutions`).
   - You can change the sentence around it: `":count Universities Available"`.
   - **Do not delete `:count`** unless you do not want the number to appear.

2. `"copyright": "© :year UniGuide Pakistan. All rights reserved."`
   - `:year` is automatically replaced with the current year (e.g. `2026`).

3. `"welcome": "Welcome back, :name"`
   - `:name` is automatically replaced with the logged-in student's name (e.g. `Welcome back, Ahmad Raza Khan`).

---

## 5. What You Should Edit vs What You Should NOT Edit

### ✅ What You SHOULD Edit:
- Marketing headlines and slogans
- Descriptions and explanatory paragraphs
- Button labels (e.g., changing "Get Started" to "Explore Now")
- Navigation menu names (e.g., changing "Programs" to "Degrees")
- Contact phone numbers, email addresses, and office locations
- Help text, empty states, and disclaimers

### ❌ What You Should NOT Put in JSON:
- **Do not add university names, fee amounts, or degree programs here.**
  - Real universities, campuses, fees, admissions, and programs are stored in the MySQL database.
  - Adding text to a JSON file will not create a new university in the system. Use the University Admin or Superadmin portal for database records.

---

## 6. How to Validate Your JSON

Before saving a file, you can check that your JSON syntax is valid:

### Option A: Using a Free Online Validator
1. Copy your JSON text.
2. Visit [jsonlint.com](https://jsonlint.com) or [codebeautify.org/jsonvalidator](https://codebeautify.org/jsonvalidator).
3. Paste your text and click **Validate JSON**.
4. If there is a missing comma or quote, the tool will point directly to the line number.

### Option B: Using the Command Line
Run this one-line command in your terminal:
```bash
php -r 'json_decode(file_get_contents("resources/content/homepage.json"), true, 512, JSON_THROW_ON_ERROR); echo "JSON is valid!\n";'
```

---

## 7. How to Restore Previous Content

If you accidentally delete something or make a syntax error:

1. **Undo in your editor**: Press `Ctrl + Z` (Windows) or `Cmd + Z` (Mac).
2. **Restore with Git**: If you have committed previous versions, run:
   ```bash
   git checkout resources/content/
   ```
   This will immediately discard your unsaved edits and restore the previous working content.

