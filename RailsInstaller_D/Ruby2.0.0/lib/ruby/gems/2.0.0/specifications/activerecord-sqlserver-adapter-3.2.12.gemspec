# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = "activerecord-sqlserver-adapter"
  s.version = "3.2.12"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Ken Collins", "Murray Steele", "Shawn Balestracci", "Joe Rafaniello", "Tom Ward"]
  s.date = "2013-07-14"
  s.description = "ActiveRecord SQL Server Adapter. For SQL Server 2005 And Higher."
  s.email = "ken@metaskills.net"
  s.homepage = "http://github.com/rails-sqlserver/activerecord-sqlserver-adapter"
  s.require_paths = ["lib"]
  s.rubyforge_project = "activerecord-sqlserver-adapter"
  s.rubygems_version = "2.0.2"
  s.summary = "ActiveRecord SQL Server Adapter. For SQL Server 2005 And Higher."

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_runtime_dependency(%q<activerecord>, ["~> 3.2.0"])
    else
      s.add_dependency(%q<activerecord>, ["~> 3.2.0"])
    end
  else
    s.add_dependency(%q<activerecord>, ["~> 3.2.0"])
  end
end
