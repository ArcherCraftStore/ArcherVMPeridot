# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = "pg"
  s.version = "0.16.0"
  s.platform = "x86-mingw32"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Michael Granger"]
  s.cert_chain = ["-----BEGIN CERTIFICATE-----\nMIIDPDCCAiSgAwIBAgIBADANBgkqhkiG9w0BAQUFADBEMQ0wCwYDVQQDDARsYXJz\nMR8wHQYKCZImiZPyLGQBGRYPZ3JlaXotcmVpbnNkb3JmMRIwEAYKCZImiZPyLGQB\nGRYCZGUwHhcNMTMwMzExMjAyMjIyWhcNMTQwMzExMjAyMjIyWjBEMQ0wCwYDVQQD\nDARsYXJzMR8wHQYKCZImiZPyLGQBGRYPZ3JlaXotcmVpbnNkb3JmMRIwEAYKCZIm\niZPyLGQBGRYCZGUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDZb4Uv\nRFJfRu/VEWiy3psh2jinETjiuBrL0NeRFGf8H7iU9+gx/DI/FFhfHGLrDeIskrJx\nYIWDMmEjVO10UUdj7wu4ZhmU++0Cd7Kq9/TyP/shIP3IjqHjVLCnJ3P6f1cl5rxZ\ngqo+d3BAoDrmPk0rtaf6QopwUw9RBiF8V4HqvpiY+ruJotP5UQDP4/lVOKvA8PI9\nP0GmVbFBrbc7Zt5h78N3UyOK0u+nvOC23BvyHXzCtcFsXCoEkt+Wwh0RFqVZdnjM\nLMO2vULHKKHDdX54K/sbVCj9pN9h1aotNzrEyo55zxn0G9PHg/G3P8nMvAXPkUTe\nbrhXrfCwWRvOXA4TAgMBAAGjOTA3MAsGA1UdDwQEAwIEsDAJBgNVHRMEAjAAMB0G\nA1UdDgQWBBRAHK81igrXodaDj8a8/BIKsaZrETANBgkqhkiG9w0BAQUFAAOCAQEA\nIswhcol3ytXthaUH3k5LopZ09viZrZHzAw0QleI3Opl/9QEGJ2BPV9+93iC0OrNL\nhmnxig6vKK1EeJ5PHXJ8hOI3nTZBrOmQcEXNBqyToP1FHMWZqwZ8wiBPXtiCqDBR\nePQ25J9xFNzQ1ItgzNSpx5cs67QNKrx5woocoBHD6kStFbshZPJx4axl3GbUFQd5\nH//3YdPQOH3jaVeUXhS+pz/gfbx8fhFAtsQ+855A3HO7g2ZRIg/atAp/0MFyn5s5\n0rq+VHOIPyvxF5khT0mYAcNmZTC8z1yPsqdgwfYNDjsSWwiIRSPUSmJRvfjM8hsW\nmMFp4kPUHbWOqCp2mz9gCA==\n-----END CERTIFICATE-----\n"]
  s.date = "2013-07-23"
  s.description = "Pg is the Ruby interface to the {PostgreSQL RDBMS}[http://www.postgresql.org/].\n\nIt works with {PostgreSQL 8.4 and later}[http://www.postgresql.org/support/versioning/].\n\nA small example usage:\n\n  #!/usr/bin/env ruby\n\n  require 'pg'\n\n  # Output a table of current connections to the DB\n  conn = PG.connect( dbname: 'sales' )\n  conn.exec( \"SELECT * FROM pg_stat_activity\" ) do |result|\n    puts \"     PID | User             | Query\"\n  result.each do |row|\n      puts \" %7d | %-16s | %s \" %\n        row.values_at('procpid', 'usename', 'current_query')\n    end\n  end"
  s.email = ["ged@FaerieMUD.org"]
  s.extra_rdoc_files = ["Contributors.rdoc", "History.rdoc", "Manifest.txt", "README-OS_X.rdoc", "README-Windows.rdoc", "README.ja.rdoc", "README.rdoc", "ext/errorcodes.txt", "POSTGRES", "LICENSE", "ext/gvl_wrappers.c", "ext/pg.c", "ext/pg_connection.c", "ext/pg_errors.c", "ext/pg_result.c"]
  s.files = ["Contributors.rdoc", "History.rdoc", "Manifest.txt", "README-OS_X.rdoc", "README-Windows.rdoc", "README.ja.rdoc", "README.rdoc", "ext/errorcodes.txt", "POSTGRES", "LICENSE", "ext/gvl_wrappers.c", "ext/pg.c", "ext/pg_connection.c", "ext/pg_errors.c", "ext/pg_result.c"]
  s.homepage = "https://bitbucket.org/ged/ruby-pg"
  s.licenses = ["BSD", "Ruby", "GPL"]
  s.rdoc_options = ["-f", "fivefish", "-t", "pg: The Ruby Interface to PostgreSQL", "-m", "README.rdoc"]
  s.require_paths = ["lib"]
  s.required_ruby_version = Gem::Requirement.new(">= 1.8.7")
  s.rubyforge_project = "pg"
  s.rubygems_version = "2.0.2"
  s.summary = "Pg is the Ruby interface to the {PostgreSQL RDBMS}[http://www.postgresql.org/]"

  if s.respond_to? :specification_version then
    s.specification_version = 4

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<hoe-mercurial>, ["~> 1.4.0"])
      s.add_development_dependency(%q<hoe-highline>, ["~> 0.1.0"])
      s.add_development_dependency(%q<rdoc>, ["~> 4.0"])
      s.add_development_dependency(%q<rake-compiler>, ["~> 0.8"])
      s.add_development_dependency(%q<hoe-deveiate>, ["~> 0.2"])
      s.add_development_dependency(%q<hoe>, ["~> 3.6"])
    else
      s.add_dependency(%q<hoe-mercurial>, ["~> 1.4.0"])
      s.add_dependency(%q<hoe-highline>, ["~> 0.1.0"])
      s.add_dependency(%q<rdoc>, ["~> 4.0"])
      s.add_dependency(%q<rake-compiler>, ["~> 0.8"])
      s.add_dependency(%q<hoe-deveiate>, ["~> 0.2"])
      s.add_dependency(%q<hoe>, ["~> 3.6"])
    end
  else
    s.add_dependency(%q<hoe-mercurial>, ["~> 1.4.0"])
    s.add_dependency(%q<hoe-highline>, ["~> 0.1.0"])
    s.add_dependency(%q<rdoc>, ["~> 4.0"])
    s.add_dependency(%q<rake-compiler>, ["~> 0.8"])
    s.add_dependency(%q<hoe-deveiate>, ["~> 0.2"])
    s.add_dependency(%q<hoe>, ["~> 3.6"])
  end
end
