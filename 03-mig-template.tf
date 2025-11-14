resource "google_compute_instance_template" "php_template_ubuntu" {
  name_prefix  = "php-ub-template-"
  machine_type = "e2-medium"

  disk {
    boot = true
    auto_delete = true
    source_image = "projects/ubuntu-os-cloud/global/images/family/ubuntu-2204-lts"
    disk_size_gb = 10
  }

  metadata = {
    enable-oslogin = "TRUE"
    startup-script = <<-EOF
  #!/bin/bash
  # Debug script
  echo "=== Startup script started ===" > /var/log/startup-debug.log
  date >> /var/log/startup-debug.log
  
  # Install Docker
  apt update -y 2>&1 >> /var/log/startup-debug.log
  apt install docker.io -y 2>&1 >> /var/log/startup-debug.log
  systemctl enable docker 2>&1 >> /var/log/startup-debug.log
  systemctl start docker 2>&1 >> /var/log/startup-debug.log
  
  echo "Docker installed" >> /var/log/startup-debug.log
  
  # Try to run container
  docker run hello-world 2>&1 >> /var/log/startup-debug.log
  echo "=== Startup script completed ===" >> /var/log/startup-debug.log
EOF
  }

  service_account {
    email = google_service_account.instance_sa.email
    scopes = ["https://www.googleapis.com/auth/cloud-platform"]
  }

  network_interface {
    network = "default"
    access_config {}
  }

  tags = ["http-server"]

  lifecycle {
    create_before_destroy = true
  }
}
