# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = "tiny_tds"
  s.version = "0.6.1"
  s.platform = "x86-mingw32"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Ken Collins", "Erik Bryn"]
  s.date = "2013-07-10"
  s.description = "TinyTDS - A modern, simple and fast FreeTDS library for Ruby using DB-Library. Developed for the ActiveRecord SQL Server adapter."
  s.email = ["ken@metaskills.net"]
  s.homepage = "http://github.com/rails-sqlserver/tiny_tds"
  s.rdoc_options = ["--charset=UTF-8"]
  s.require_paths = ["lib"]
  s.rubygems_version = "2.0.2"
  s.summary = "TinyTDS - A modern, simple and fast FreeTDS library for Ruby using DB-Library."

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<rake>, ["~> 0.9.2"])
      s.add_development_dependency(%q<mini_portile>, ["~> 0.5.0"])
      s.add_development_dependency(%q<rake-compiler>, ["= 0.8.1"])
      s.add_development_dependency(%q<activesupport>, ["~> 3.0"])
      s.add_development_dependency(%q<minitest>, [">= 0"])
      s.add_development_dependency(%q<connection_pool>, ["~> 0.9.2"])
    else
      s.add_dependency(%q<rake>, ["~> 0.9.2"])
      s.add_dependency(%q<mini_portile>, ["~> 0.5.0"])
      s.add_dependency(%q<rake-compiler>, ["= 0.8.1"])
      s.add_dependency(%q<activesupport>, ["~> 3.0"])
      s.add_dependency(%q<minitest>, [">= 0"])
      s.add_dependency(%q<connection_pool>, ["~> 0.9.2"])
    end
  else
    s.add_dependency(%q<rake>, ["~> 0.9.2"])
    s.add_dependency(%q<mini_portile>, ["~> 0.5.0"])
    s.add_dependency(%q<rake-compiler>, ["= 0.8.1"])
    s.add_dependency(%q<activesupport>, ["~> 3.0"])
    s.add_dependency(%q<minitest>, [">= 0"])
    s.add_dependency(%q<connection_pool>, ["~> 0.9.2"])
  end
end
