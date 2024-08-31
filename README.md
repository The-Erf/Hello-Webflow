# 🌐 Simple Reverse Proxy for Webflow Websites

> **Enhance performance, reduce load times, and improve compatibility for Webflow-built websites.**

![Reverse Proxy Banner](https://via.placeholder.com/800x200.png?text=Simple+Reverse+Proxy+for+Webflow+Websites)

## 🚀 Purpose

This project provides a **simple reverse proxy** solution for websites built with Webflow. The primary goals are to:

- Boost **performance** and **loading speed**.
- Increase **compatibility** across different environments.
- Implement **content compression** to minimize data transfer size and further improve speed.

## 💡 Features

- 🗃️ **Caching Static Content:** Store and serve static assets to reduce server load and improve load times.
- 🗜️ **Content Compression:** Compress web content to reduce the size of data transferred.
- 🔄 **Proxy Creation:** Set up a reverse proxy in development or testing environments.

## 📋 Suggested Use Cases

1. Caching static content for quicker load times.
2. Compressing content to minimize data transfer.
3. Creating a proxy in development or testing scenarios.

## ⚙️ Installation

To set up the reverse proxy, follow these simple steps:

1. **Download the `index.php` file:**
   - [Download the `index.php` file from the repository](https://github.com/The.Erf/Hello-Webflow/index.php).

2. **Upload `index.php` to Your Shared Host:**
   - Use FTP or your hosting provider's file manager to upload the `index.php` file to the root directory of your shared host.

3. **Configure the Target Domain:**
   - Open the `index.php` file in any text editor.
   - Locate the following lines at the beginning of the file:

    ```php
    <?php

    $target_ip = 'example.webflow.io';
    $target_port = '443';
    ```

   - Replace `'example.webflow.io'` with your Webflow domain (e.g., `yourwebsite.webflow.io`).

4. **Save the Changes:**
   - Save the modified `index.php` file and re-upload it if necessary.

5. **Access Your Proxy:**
   - Open your browser and go to your shared host domain to see your Webflow website loaded through the reverse proxy.

That's it! Your reverse proxy is now set up and ready to use.

## 🔓 License

This project is open source and available under the **MIT License**. You are free to use, modify, and distribute it as you wish, provided that the original license is included in any copies or substantial portions of the project.

For more details, see the [LICENSE](LICENSE) file.

## ⚠️ Disclaimer of Liability

The author of this project is not responsible for any misuse or breach of Webflow's terms and conditions by users.

---

> *Developed with ❤️ by [The.Erf](https://github.com/The-Erf)*

